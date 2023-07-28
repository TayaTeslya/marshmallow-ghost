<?php
function loadConfig(string $configName): array
{
    return include dirname(__DIR__) . "/config/$configName.php" ?: [];
}
