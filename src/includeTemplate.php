<?php

function includeTemplate(string $templatePath, array $data = []): void
{
    extract($data);
    include dirname(__DIR__) . '/templates/' . ltrim($templatePath, '/');
}
