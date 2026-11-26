<?php

declare(strict_types=1);

/**
 * Skrip verifikasi mekanis (6 Hukum Arsitektur & Tata Kelola) untuk RDM MTs Persis 23.
 */
$repoRoot = dirname(__DIR__);
$violations = [];

$addViolation = static function (int $law, string $file, string $message) use (&$violations, $repoRoot): void {
    $relative = ltrim(str_replace([$repoRoot, '\\'], ['', '/'], $file), '/');
    $violations[] = [
        'law' => $law,
        'file' => $relative,
        'message' => $message,
    ];
};

$walkFiles = static function (string $dir, callable $callback) use (&$walkFiles): void {
    if (! is_dir($dir)) {
        return;
    }

    $items = scandir($dir);
    if ($items === false) {
        return;
    }

    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }

        $path = $dir.DIRECTORY_SEPARATOR.$item;
        if (is_dir($path)) {
            $walkFiles($path, $callback);
        } elseif (is_file($path)) {
            $callback($path);
        }
    }
};

// ------------------------------------------------------------
// Hukum 5: Integritas AGENTS.md, Skills, ADR, dan Tautan Docs
// ------------------------------------------------------------
$agentsPath = $repoRoot.DIRECTORY_SEPARATOR.'AGENTS.md';
if (! is_file($agentsPath)) {
    $addViolation(5, $agentsPath, 'File AGENTS.md wajib ada di root proyek.');
} else {
    $agentsContent = (string) file_get_contents($agentsPath);
    $agentsLines = count(preg_split('/\R/', rtrim($agentsContent)) ?: []);
    if (str_starts_with(ltrim($agentsContent), '---')) {
        $addViolation(5, $agentsPath, 'AGENTS.md tidak boleh memiliki frontmatter YAML.');
    }
    if ($agentsLines > 120) {
        $addViolation(5, $agentsPath, "AGENTS.md melebihi batas 120 baris (saat ini {$agentsLines} baris).");
    }
}

$walkFiles($repoRoot.DIRECTORY_SEPARATOR.'.agents'.DIRECTORY_SEPARATOR.'skills', static function (string $file) use ($addViolation): void {
    if (basename($file) !== 'SKILL.md') {
        return;
    }

    $content = (string) file_get_contents($file);
    if (! str_starts_with($content, '---') || ! str_contains($content, 'name:') || ! str_contains($content, 'description:')) {
        $addViolation(5, $file, 'File SKILL.md wajib memiliki frontmatter YAML dengan field name: dan description:.');
    }
});

$walkFiles($repoRoot.DIRECTORY_SEPARATOR.'docs'.DIRECTORY_SEPARATOR.'adr', static function (string $file) use ($addViolation): void {
    if (! str_ends_with($file, '.md')) {
        return;
    }

    $content = (string) file_get_contents($file);
    foreach (['Status', 'Konteks', 'Keputusan', 'Konsekuensi'] as $section) {
        if (! preg_match('/^##?\s+'.preg_quote($section, '/').'\b/im', $content)) {
            $addViolation(5, $file, "ADR wajib memuat bagian \"{$section}\".");
        }
    }
});

$checkMarkdownLinks = static function (string $file) use ($addViolation): void {
    $content = (string) file_get_contents($file);
    $contentWithoutCodeBlocks = preg_replace('/```[\s\S]*?```/', '', $content) ?? $content;

    if (preg_match_all('/\[[^\]]+\]\(([^)]+)\)/', $contentWithoutCodeBlocks, $matches)) {
        $baseDir = dirname($file);
        foreach ($matches[1] as $target) {
            if (preg_match('/^(https?:|mailto:|#)/i', $target)) {
                continue;
            }
            $cleanTarget = explode('#', $target)[0];
            if ($cleanTarget === '') {
                continue;
            }
            $resolved = $baseDir.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $cleanTarget);
            if (! file_exists($resolved)) {
                $addViolation(5, $file, "Tautan relatif rusak ke \"{$target}\".");
            }
        }
    }
};

if (is_file($agentsPath)) {
    $checkMarkdownLinks($agentsPath);
}
$walkFiles($repoRoot.DIRECTORY_SEPARATOR.'docs', static function (string $file) use ($checkMarkdownLinks): void {
    if (str_ends_with($file, '.md')) {
        $checkMarkdownLinks($file);
    }
});

// ------------------------------------------------------------
// Hukum 3: Isolasi Rahasia & .env
// ------------------------------------------------------------
$envExamplePath = $repoRoot.DIRECTORY_SEPARATOR.'.env.example';
if (is_file($envExamplePath)) {
    $lines = file($envExamplePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed === '' || str_starts_with($trimmed, '#') || ! str_contains($trimmed, '=')) {
            continue;
        }
        [$key, $rawValue] = explode('=', $trimmed, 2);
        $value = trim(trim($rawValue), "\"'");
        if ($value !== '' && preg_match('/SECRET|PASSWORD|PRIVATE_KEY|TOKEN|API_KEY/i', $key)) {
            $isPlaceholder = $value === 'null'
                || str_starts_with($value, 'placeholder')
                || str_starts_with($value, 'your-');
            if (! $isPlaceholder) {
                $addViolation(3, $envExamplePath, "Nilai sensitif non-placeholder terdeteksi pada {$key} di .env.example.");
            }
        }
    }
}

if (is_dir($repoRoot.DIRECTORY_SEPARATOR.'.git')) {
    $trackedEnv = [];
    @exec('git -C '.escapeshellarg($repoRoot).' ls-files ".env*"', $trackedEnv);
    foreach ($trackedEnv as $trackedFile) {
        $base = basename(trim($trackedFile));
        if ($base !== '' && $base !== '.env.example') {
            $addViolation(3, $repoRoot.DIRECTORY_SEPARATOR.$trackedFile, "File rahasia {$base} tidak boleh ter-track di Git.");
        }
    }
}

// ------------------------------------------------------------
// Hukum 1, 3, 4, 6: Pemeriksaan File PHP & Blade
// ------------------------------------------------------------
foreach (['app', 'routes', 'database'] as $phpDir) {
    $walkFiles($repoRoot.DIRECTORY_SEPARATOR.$phpDir, static function (string $file) use ($addViolation): void {
        if (! str_ends_with($file, '.php')) {
            return;
        }

        $content = (string) file_get_contents($file);

        // Hukum 3: env() di luar config/
        if (preg_match('/\benv\s*\(/', $content)) {
            $addViolation(3, $file, 'Penggunaan fungsi env() dilarang di luar direktori config/. Gunakan config().');
        }

        // Hukum 4: Cek role mentah
        if (preg_match('/\bhasRole\s*\(|->role\s*[!=]==?/', $content)) {
            $addViolation(4, $file, 'Cek role mentah (hasRole / ->role ===) dilarang. Gunakan granular permission melalui Policy/Gate.');
        }

        // Hukum 4: DB:: langsung di Controller atau Livewire
        $normalized = str_replace('\\', '/', $file);
        if (str_contains($normalized, '/Controllers/') || str_contains($normalized, '/Livewire/')) {
            if (preg_match('/\bDB::|Illuminate\\\\Support\\\\Facades\\\\DB\b/', $content)) {
                $addViolation(4, $file, 'Query DB:: langsung dilarang di Controller/Livewire. Pindahkan ke Action atau Query class.');
            }
        }

        // Hukum 6: Fungsi debug tertinggal
        if (preg_match('/\b(dd|dump|ray)\s*\(/', $content)) {
            $addViolation(6, $file, 'Fungsi debug (dd/dump/ray) tertinggal di kode.');
        }
    });
}

$walkFiles($repoRoot.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'views', static function (string $file) use ($addViolation): void {
    if (! str_ends_with($file, '.blade.php')) {
        return;
    }

    $normalized = str_replace('\\', '/', $file);
    $content = (string) file_get_contents($file);

    // Hukum 4: DB:: atau env() di Blade
    if (preg_match('/\bDB::|Illuminate\\\\Support\\\\Facades\\\\DB\b/', $content)) {
        $addViolation(4, $file, 'Query DB:: dilarang berada di Blade view.');
    }
    if (preg_match('/\benv\s*\(/', $content)) {
        $addViolation(3, $file, 'Fungsi env() dilarang berada di Blade view.');
    }
    if (preg_match('/\bhasRole\s*\(|->role\s*[!=]==?/', $content)) {
        $addViolation(4, $file, 'Cek role mentah dilarang di Blade view. Gunakan @can atau permission check.');
    }

    $isUiPrimitive = str_contains($normalized, '/resources/views/components/ui/');
    $isLayoutShell = str_contains($normalized, '/resources/views/layouts/');
    $isPdfTemplate = str_contains($normalized, '/resources/views/pdf/');
    $isWelcomeStub = str_ends_with($normalized, '/resources/views/welcome.blade.php');

    if (! $isPdfTemplate && ! $isWelcomeStub) {
        // Hukum 1: Warna arbitrer, gradient dekoratif, dan emoji UI
        if (preg_match('/(?:bg|text|border|fill|stroke|ring|accent|outline|from|to|via)-\[(?:#[0-9a-fA-F]+|(?:rgb|rgba|hsl|hsla)\([^\]]+\))\]/', $content)) {
            $addViolation(1, $file, 'Warna hex/rgb arbitrer dilarang di UI. Gunakan token tema daisyUI rdm.');
        }
        if (preg_match('/\bbg-(?:gradient|linear)-/', $content)) {
            $addViolation(1, $file, 'Gradient dekoratif dilarang di UI modul.');
        }
        if (preg_match('/[\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}]/u', $content)) {
            $addViolation(1, $file, 'Emoji dilarang dipakai pada elemen UI aplikasi.');
        }
    }

    // Hukum 1: Kontrol HTML mentah di view modul/halaman (di luar primitives, layout shell, pdf, welcome stub)
    if (! $isUiPrimitive && ! $isLayoutShell && ! $isPdfTemplate && ! $isWelcomeStub) {
        if (preg_match('/<(button|input|select|textarea|table)\b/i', $content, $match)) {
            $tag = strtolower($match[1]);
            $addViolation(1, $file, "Kontrol HTML mentah <{$tag}> dilarang di view modul. Gunakan komponen <x-ui.*>.");
        }
    }
});

if ($violations !== []) {
    fwrite(STDERR, "\n[GUARDRAIL GAGAL] Ditemukan ".count($violations)." pelanggaran:\n");
    foreach ($violations as $violation) {
        fwrite(STDERR, "  - [Hukum {$violation['law']}] {$violation['file']}: {$violation['message']}\n");
    }
    exit(1);
}

fwrite(STDOUT, "[GUARDRAILS LOLOS] Seluruh 6 Hukum Mekanis terpenuhi.\n");
exit(0);
