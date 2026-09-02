<?php
// Defina uma chave secreta para proteger seu script
define('SECRET_TOKEN', 'secret_token');

// Valida a chave de acesso enviada pela URL
if (!isset($_GET['token']) || $_GET['token'] !== SECRET_TOKEN) {
    http_response_code(403);
    die('Acesso negado.');
}

// URL do ZIP da branch principal do seu repositório público
$repoZipUrl = 'https://github.com/alinebritos/aline-wp-theme/archive/refs/heads/main.zip';
$zipFile = __DIR__ . '/theme_latest.zip';
$extractPath = __DIR__;

// 1. Baixa o arquivo ZIP do GitHub
$zipContent = @file_get_contents($repoZipUrl);
if ($zipContent === false) {
    die('Erro ao baixar os arquivos do GitHub.');
}
file_put_contents($zipFile, $zipContent);

// 2. Extrai o arquivo ZIP
$zip = new ZipArchive;
if ($zip->open($zipFile) === TRUE) {
    $zip->extractTo($extractPath);
    $zip->close();
    @unlink($zipFile);

    // O GitHub coloca os arquivos dentro de uma subpasta chamada 'aline-wp-theme-main'
    $extractedFolder = $extractPath . '/aline-wp-theme-main';

    if (is_dir($extractedFolder)) {
        // Move todos os arquivos da subpasta para a raiz do tema
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($extractedFolder, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $relativePath = substr($fileinfo->getPathname(), strlen($extractedFolder) + 1);
            $targetPath = $extractPath . '/' . $relativePath;

            if ($fileinfo->isDir()) {
                if (!is_dir($targetPath)) {
                    mkdir($targetPath, 0755, true);
                }
            } else {
                rename($fileinfo->getPathname(), $targetPath);
            }
        }

        // Limpa as pastas temporárias restantes
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($extractedFolder, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($iterator as $item) {
            if ($item->isDir()) {
                @rmdir($item->getPathname());
            } else {
                @unlink($item->getPathname());
            }
        }
        @rmdir($extractedFolder);
    }

    echo "Deploy realizado com sucesso!";
} else {
    echo "Falha ao descompactar o arquivo.";
}