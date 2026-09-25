<?php

declare(strict_types=1);

namespace App;

use RuntimeException;
use Symfony\Component\Process\Process;

class ResumeChecker
{
    public const MAX_BYTES = 10 * 1024 * 1024;

    public const TIMEOUT = 60;

    public const KEYWORD_LIMIT = 30;

    private const SECTIONS = [
        'summary' => ['summary', 'profile', 'objective', 'about me', 'ringkasan', 'profil', 'objektif'],
        'experience' => ['experience', 'employment', 'work history', 'career history', 'pengalaman', 'sejarah pekerjaan'],
        'education' => ['education', 'academic', 'pendidikan', 'akademik'],
        'skills' => ['skills', 'technical skills', 'competencies', 'kemahiran', 'kompetensi'],
        'projects' => ['projects', 'portfolio', 'projek'],
        'certifications' => ['certification', 'certifications', 'certificates', 'courses', 'pensijilan', 'sijil', 'kursus'],
        'languages' => ['languages', 'language ability', 'bahasa'],
        'awards' => ['achievements', 'awards', 'honours', 'honors', 'pencapaian', 'anugerah'],
    ];

    private const STOPWORDS = [
        'a', 'able', 'about', 'after', 'again', 'all', 'also', 'am', 'an', 'and', 'any', 'are', 'as', 'at',
        'be', 'because', 'been', 'before', 'being', 'between', 'both', 'but', 'by', 'can', 'candidate', 'company',
        'could', 'did', 'do', 'does', 'doing', 'each', 'etc', 'few', 'for', 'from', 'further', 'good', 'had',
        'has', 'have', 'having', 'he', 'her', 'here', 'his', 'how', 'however', 'i', 'if', 'in', 'into', 'is',
        'it', 'its', 'job', 'join', 'looking', 'may', 'me', 'might', 'more', 'most', 'must', 'my', 'new', 'no',
        'nor', 'not', 'of', 'on', 'once', 'only', 'or', 'other', 'our', 'ours', 'over', 'own', 'plus', 'position',
        'preferred', 'required', 'requirements', 'responsibilities', 'role', 'same', 'she', 'should', 'so', 'some',
        'strong', 'such', 'team', 'than', 'that', 'the', 'their', 'them', 'then', 'there', 'these', 'they', 'this',
        'those', 'to', 'too', 'under', 'us', 'very', 'was', 'we', 'well', 'were', 'what', 'when', 'where', 'which',
        'while', 'who', 'whom', 'why', 'will', 'with', 'work', 'would', 'you', 'your', 'yours',
        'ada', 'adalah', 'agar', 'akan', 'amat', 'anda', 'antara', 'apa', 'bagi', 'bahawa', 'bila', 'boleh',
        'calon', 'dalam', 'dan', 'dari', 'dengan', 'di', 'dia', 'hendak', 'hanya', 'ini', 'itu', 'jabatan',
        'jika', 'juga', 'kalau', 'kami', 'kepada', 'kerja', 'kerana', 'ke', 'kurang', 'lain', 'lebih', 'mampu',
        'mana', 'masih', 'mengenai', 'melalui', 'mereka', 'mesti', 'nak', 'oleh', 'pada', 'pengalaman', 'perlu',
        'sahaja', 'satu', 'saya', 'sebab', 'secara', 'sehingga', 'seperti', 'sebagai', 'serta', 'setiap', 'siapa',
        'syarikat', 'sudah', 'supaya', 'tak', 'telah', 'tentang', 'terhadap', 'tidak', 'termasuk', 'untuk', 'yang',
    ];

    public function __construct(private readonly ?string $binary = null)
    {
    }

    public function binary(): string
    {
        return $this->binary ?? (getenv('GS_BINARY') ?: 'gs');
    }

    public function analyse(string $pdf, string $jobDescription = ''): array
    {
        $text = $this->extractText($pdf);
        $pages = $this->pageCount($pdf);
        $words = $this->wordCount($text);

        $checks = $this->checks($pdf, $text, $pages, $words);
        $total = array_sum(array_column($checks, 'weight'));
        $earned = 0.0;

        foreach ($checks as $check) {
            $earned += match ($check['status']) {
                'pass' => $check['weight'],
                'warn' => $check['weight'] / 2,
                default => 0.0,
            };
        }

        return [
            'score' => (int) round($earned / max($total, 1) * 100),
            'words' => $words,
            'pages' => $pages,
            'checks' => $checks,
            'match' => trim($jobDescription) === '' ? null : $this->keywordMatch($text, $jobDescription),
        ];
    }

    private function checks(string $pdf, string $text, ?int $pages, int $words): array
    {
        $checks = [
            $this->textCheck($words),
            $this->contactCheck($text),
            $this->sectionsCheck($text),
            $this->datesCheck($text),
            $this->bulletsCheck($text),
            $this->lengthCheck($pages, $words),
            $this->layoutCheck($text),
            $this->sizeCheck(strlen($pdf)),
            $this->encodingCheck($text),
        ];

        if ($words < 30) {
            foreach ($checks as $index => $check) {
                if (in_array($check['id'], ['contact', 'sections', 'dates', 'bullets', 'layout', 'encoding'], true)) {
                    $checks[$index]['status'] = 'fail';
                }
            }
        }

        return $checks;
    }

    private function textCheck(int $words): array
    {
        $status = $words >= 100 ? 'pass' : ($words >= 30 ? 'warn' : 'fail');

        return $this->check('text', 25, $status, ['words' => $words]);
    }

    private function contactCheck(string $text): array
    {
        $found = [];

        if (preg_match('/[\w.+-]+@[\w-]+\.[\w.-]+/', $text)) {
            $found[] = 'email';
        }

        if (preg_match('/\+?\d[\d\s().-]{7,}\d/', $text)) {
            $found[] = 'phone';
        }

        if (preg_match('/linkedin\.com|github\.com|https?:\/\//i', $text)) {
            $found[] = 'online';
        }

        $status = 'fail';

        if (in_array('email', $found, true) && in_array('phone', $found, true)) {
            $status = 'pass';
        } elseif ($found !== []) {
            $status = 'warn';
        }

        return $this->check('contact', 15, $status, ['found' => $found]);
    }

    private function sectionsCheck(string $text): array
    {
        $lines = $this->headingLines($text);
        $found = [];

        foreach (self::SECTIONS as $id => $needles) {
            foreach ($lines as $line) {
                if ($this->containsAny($line, $needles)) {
                    $found[] = $id;

                    break;
                }
            }
        }

        $count = count($found);
        $status = $count >= 3 ? 'pass' : ($count >= 1 ? 'warn' : 'fail');

        return $this->check('sections', 15, $status, ['count' => $count, 'found' => $found]);
    }

    private function datesCheck(string $text): array
    {
        preg_match_all('/\b(?:19|20)\d{2}\b/', $text, $matches);
        $count = count(array_unique($matches[0]));
        $status = $count >= 2 ? 'pass' : ($count === 1 ? 'warn' : 'fail');

        return $this->check('dates', 10, $status, ['count' => $count]);
    }

    private function bulletsCheck(string $text): array
    {
        $count = 0;

        foreach ($this->lines($text) as $line) {
            if (preg_match('/^\s*(?:[•●▪◦‣·*\-–—]|\d{1,2}[.)])\s+\S/u', $line)) {
                $count++;
            }
        }

        $status = $count >= 3 ? 'pass' : ($count >= 1 ? 'warn' : 'fail');

        return $this->check('bullets', 10, $status, ['count' => $count]);
    }

    private function lengthCheck(?int $pages, int $words): array
    {
        if ($pages !== null) {
            $status = $pages <= 2 ? 'pass' : ($pages === 3 ? 'warn' : 'fail');
        } else {
            $status = $words >= 300 ? 'pass' : ($words >= 150 ? 'warn' : 'fail');
        }

        return $this->check('length', 10, $status, ['pages' => $pages, 'words' => $words]);
    }

    private function layoutCheck(string $text): array
    {
        $lines = 0;
        $gaps = 0;

        foreach ($this->lines($text) as $line) {
            if (trim($line) === '') {
                continue;
            }

            $lines++;

            if (preg_match('/\S {3,}\S.*? {3,}\S/u', $line)) {
                $gaps++;
            }
        }

        $percent = $lines === 0 ? 0 : (int) round($gaps / $lines * 100);
        $status = $percent >= 50 ? 'warn' : 'pass';

        return $this->check('layout', 5, $status, ['percent' => $percent]);
    }

    private function sizeCheck(int $bytes): array
    {
        $status = $bytes <= 5 * 1024 * 1024 ? 'pass' : 'warn';

        return $this->check('size', 5, $status, ['size' => human_size($bytes)]);
    }

    private function encodingCheck(string $text): array
    {
        $count = substr_count($text, "\u{FFFD}") + substr_count($text, '(cid:');
        $status = $count === 0 ? 'pass' : ($count < 10 ? 'warn' : 'fail');

        return $this->check('encoding', 5, $status, ['count' => $count]);
    }

    private function keywordMatch(string $resume, string $jobDescription): array
    {
        $tokens = $this->tokens($jobDescription);
        $candidates = array_count_values($tokens);

        foreach (array_keys($candidates) as $word) {
            if (in_array($word, self::STOPWORDS, true)) {
                unset($candidates[$word]);
            }
        }

        $phrases = [];
        $previous = null;

        foreach ($tokens as $token) {
            if (
                $previous !== null
                && ! in_array($previous, self::STOPWORDS, true)
                && ! in_array($token, self::STOPWORDS, true)
            ) {
                $phrase = $previous . ' ' . $token;
                $phrases[$phrase] = ($phrases[$phrase] ?? 0) + 1;
            }

            $previous = $token;
        }

        foreach ($phrases as $phrase => $count) {
            if ($count >= 2) {
                $candidates[$phrase] = $count;
            }
        }

        arsort($candidates);
        $keywords = array_slice(array_keys($candidates), 0, self::KEYWORD_LIMIT);

        $haystack = mb_strtolower((string) preg_replace('/\s+/u', ' ', $resume));
        $found = [];
        $missing = [];

        foreach ($keywords as $keyword) {
            $pattern = '/(?<![\w+#])' . preg_quote($keyword, '/') . '(?![\w+#])/u';

            if (preg_match($pattern, $haystack)) {
                $found[] = $keyword;
            } else {
                $missing[] = $keyword;
            }
        }

        $total = count($keywords);

        return [
            'found' => $found,
            'missing' => $missing,
            'total' => $total,
            'percent' => $total === 0 ? 0 : (int) round(count($found) / $total * 100),
        ];
    }

    private function extractText(string $pdf): string
    {
        $process = $this->process($pdf, [
            '-sDEVICE=txtwrite',
            '-sOutputFile=-',
            '-',
        ]);

        return $process->getOutput();
    }

    private function pageCount(string $pdf): ?int
    {
        try {
            $process = $this->process($pdf, [
                '-sDEVICE=inkcov',
                '-sOutputFile=-',
                '-',
            ]);
        } catch (RuntimeException) {
            return null;
        }

        $lines = array_filter(array_map('trim', $this->lines($process->getOutput())));

        return $lines === [] ? null : count($lines);
    }

    private function process(string $pdf, array $arguments): Process
    {
        $process = new Process([
            $this->binary(),
            '-q',
            '-dNOPAUSE',
            '-dBATCH',
            ...$arguments,
        ]);

        $process->setInput($pdf);
        $process->setTimeout(self::TIMEOUT);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException(
                trim($process->getErrorOutput()) ?: 'Ghostscript gagal membaca PDF ni.'
            );
        }

        return $process;
    }

    private function wordCount(string $text): int
    {
        return count(preg_split('/\s+/u', trim($text), -1, PREG_SPLIT_NO_EMPTY) ?: []);
    }

    private function headingLines(string $text): array
    {
        $lines = [];

        foreach ($this->lines($text) as $line) {
            $line = rtrim((string) preg_replace('/\s+/', ' ', strtolower(trim($line))), ':');

            if ($line !== '' && mb_strlen($line) <= 48) {
                $lines[] = $line;
            }
        }

        return $lines;
    }

    private function containsAny(string $line, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($line, $needle)) {
                return true;
            }
        }

        return false;
    }

    private function tokens(string $text): array
    {
        preg_match_all('/[a-z][a-z0-9+#.\-]*/u', mb_strtolower($text), $matches);
        $tokens = [];

        foreach ($matches[0] as $token) {
            $token = trim($token, '.-');

            if (mb_strlen($token) >= 2) {
                $tokens[] = $token;
            }
        }

        return $tokens;
    }

    private function lines(string $text): array
    {
        return preg_split('/\R/u', $text) ?: [];
    }

    private function check(string $id, int $weight, string $status, array $facts = []): array
    {
        return [
            'id' => $id,
            'weight' => $weight,
            'status' => $status,
            'facts' => $facts,
        ];
    }
}
