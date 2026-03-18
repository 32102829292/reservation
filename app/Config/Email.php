<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = '';
    public string $fromName   = '';
    public string $recipients = '';

    public string $userAgent = 'CodeIgniter';
    public string $protocol  = 'smtp';
    public string $mailPath  = '/usr/sbin/sendmail';

    public string $SMTPHost     = '';
    public string $SMTPUser     = '';
    public string $SMTPPass     = '';
    public int    $SMTPPort      = 587;
    public int    $SMTPTimeout   = 30;
    public bool   $SMTPKeepAlive = false;
    public string $SMTPCrypto    = 'tls';

    public bool   $wordWrap  = true;
    public int    $wrapChars = 76;
    public string $mailType  = 'html';
    public string $charset   = 'UTF-8';
    public bool   $validate  = false;
    public int    $priority  = 3;
    public string $CRLF      = "\r\n";
    public string $newline   = "\r\n";
    public bool   $BCCBatchMode = false;
    public int    $BCCBatchSize = 200;
    public bool   $DSN          = false;

    public function __construct()
    {
        parent::__construct();

        $this->fromEmail   = env('EMAIL_FROM_ADDRESS', 'prttypriyaa@gmail.com');
        $this->fromName    = env('EMAIL_FROM_NAME',    'E-Learning Reservation System');
        $this->SMTPHost    = env('EMAIL_SMTP_HOST',    'smtp-relay.brevo.com');
        $this->SMTPUser    = env('EMAIL_SMTP_USER',    'a4aed9001@smtp-brevo.com');
        $this->SMTPPass    = env('EMAIL_SMTP_PASS',    '');
        $this->SMTPPort    = (int) env('EMAIL_SMTP_PORT',   587);
        $this->SMTPCrypto  = env('EMAIL_SMTP_CRYPTO',  'tls');
        $this->SMTPTimeout = (int) env('EMAIL_SMTP_TIMEOUT', 30);
    }
}