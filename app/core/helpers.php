<?php

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function base_path(): string
{
    if (PHP_SAPI === 'cli-server') {
        return '';
    }

    return rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
}

function url(string $path = ''): string
{
    return base_path() . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

function request_segments(): array
{
    $path = $_GET['url'] ?? null;

    if (!is_string($path)) {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $path = substr($uri, strlen(base_path()));
    }

    $path = trim($path, '/');

    return $path === '' ? [] : explode('/', $path);
}

function current_segment(): string
{
    return request_segments()[0] ?? 'catalogue';
}

function nav_active(string $segment, ?string $action = null): string
{
    $parts = request_segments();

    if (($parts[0] ?? 'catalogue') !== $segment) {
        return '';
    }

    $current = $parts[1] ?? 'index';

    if ($action === null) {
        return in_array($current, ['retards', 'historique'], true) ? '' : ' active';
    }

    return $current === $action ? ' active' : '';
}

function get_string(string $key, int $max = 100): string
{
    $value = $_GET[$key] ?? '';

    return is_string($value) ? mb_substr(trim($value), 0, $max) : '';
}

function initial(string $text): string
{
    return mb_strtoupper(mb_substr(trim($text), 0, 1, 'UTF-8'), 'UTF-8');
}

function cover_style(string $key): string
{
    $hue = crc32($key) % 360;
    $hue2 = ($hue + 40) % 360;

    return "background: linear-gradient(135deg, hsl($hue 70% 38%), hsl($hue2 75% 18%));";
}

function admin_home(): string
{
    return 'dashboard';
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function field(array $input, string $key): string
{
    $value = $input[$key] ?? '';

    return is_string($value) ? trim($value) : '';
}

function old(array $data, string $key, $default = '')
{
    return $data[$key] ?? $default;
}

function pagination(int $page, int $total, int $perPage, string $path, array $query = []): string
{
    $pages = (int) ceil($total / $perPage);

    if ($pages <= 1) {
        return '';
    }

    $html = '<nav aria-label="Pagination"><ul class="pagination justify-content-center">';

    for ($i = 1; $i <= $pages; $i++) {
        $link = url($path) . '?' . http_build_query($query + ['page' => $i]);
        $html .= '<li class="page-item' . ($i === $page ? ' active' : '') . '">'
               . '<a class="page-link" href="' . e($link) . '">' . $i . '</a></li>';
    }

    return $html . '</ul></nav>';
}
