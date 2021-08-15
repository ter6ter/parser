<?php
function sig_handler($signo) {
    switch ($signo) {
        case SIGTERM:
        case SIGINT:
            exit;
    }
}

function isDaemonActive($pid_file) {
    if( is_file($pid_file) ) {
        $pid = file_get_contents($pid_file);
        //проверяем на наличие процесса
        if(posix_kill($pid,0)) {
            //демон уже запущен
            return true;
        } else {
            //pid-файл есть, но процесса нет
            if(!unlink($pid_file)) {
                //не могу уничтожить pid-файл. ошибка
                exit(-1);
            }
        }
    }
    return false;
}

if (isDaemonActive('/tmp/parser_daemon.pid')) {
    echo 'Daemon already active';
    exit;
}

$pid = pcntl_fork();
declare(ticks=1);
if ($pid == -1) {
    die('Error fork process' . PHP_EOL);
} elseif ($pid) {
    exit();
} else {
    pcntl_signal(SIGTERM, "sig_handler");
    pcntl_signal(SIGINT,  "sig_handler");
    while(true) {
        exec('php artisan command:parse');
        sleep(600);
    }
}
posix_setsid();
