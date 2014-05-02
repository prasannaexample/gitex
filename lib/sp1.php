<?php

class Netenberg_Script_Name extends Netenberg_Script
{
    public function install($parameters)
    {
        $control_panel = Zend_Registry::get('control_panel');
        $operating_system = Zend_Registry::get('operating_system');

        $curl = new Netenberg_cURL;

        $step = 0;

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        list(
            $parameters['mysql_hostname'],
            $parameters['mysql_username'],
            $parameters['mysql_password'],
            $parameters['mysql_database']
        ) = $control_panel->insertMySQL();

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        $operating_system->transpose(
            'http://192.168.1.103/~dinesh/files/Dolphin-v.7.1.4.zip',
            array(
                'FOLDER/*' => sprintf(
                    '%s/%s',
                    $parameters['document_root'],
                    $parameters['directory']
                ),
            )
        );

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        if (!$control_panel->hassuEXEC()) {
            $operating_system->chmod(sprintf(
                '%s/%s/backup',
                $parameters['document_root'],
                $parameters['directory']
            ), 777, true);
        }

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        if (!$control_panel->hassuEXEC()) {
            $operating_system->chmod(sprintf(
                '%s/%s/cache',
                $parameters['document_root'],
                $parameters['directory']
            ), 777, true);
        }

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        if (!$control_panel->hassuEXEC()) {
            $operating_system->chmod(sprintf(
                '%s/%s/cache_public',
                $parameters['document_root'],
                $parameters['directory']
            ), 777, true);
        }

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        if (!$control_panel->hassuEXEC()) {
            $operating_system->chmod(sprintf(
                '%s/%s/flash',
                $parameters['document_root'],
                $parameters['directory']
            ), 777, true);
        }

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        if (!$control_panel->hassuEXEC()) {
            $operating_system->chmod(sprintf(
                '%s/%s/inc',
                $parameters['document_root'],
                $parameters['directory']
            ), 777, true);
        }

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        if (!$control_panel->hassuEXEC()) {
            $operating_system->chmod(sprintf(
                '%s/%s/media',
                $parameters['document_root'],
                $parameters['directory']
            ), 777, true);
        }

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        if (!$control_panel->hassuEXEC()) {
            $operating_system->chmod(sprintf(
                '%s/%s/plugins',
                $parameters['document_root'],
                $parameters['directory']
            ), 777, true);
        }

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        if (!$control_panel->hassuEXEC()) {
            $operating_system->chmod(sprintf(
                '%s/%s/sitemap.xml',
                $parameters['document_root'],
                $parameters['directory']
            ), 777, false);
        }

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        if (!$control_panel->hassuEXEC()) {
            $operating_system->chmod(sprintf(
                '%s/%s/tmp',
                $parameters['document_root'],
                $parameters['directory']
            ), 777, true);
        }

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        $operating_system->cp(sprintf(
            '%s/%s/install/langs/lang-en.php',
            $parameters['document_root'],
            $parameters['directory']
        ), sprintf(
            '%s/%s/langs',
            $parameters['document_root'],
            $parameters['directory']
        ));

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        if (!$control_panel->hassuEXEC()) {
            $operating_system->chmod(sprintf(
                '%s/%s/langs',
                $parameters['document_root'],
                $parameters['directory']
            ), 777, true);
        }

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        $parse_url = parse_url(sprintf(
            'http://%s/%s', $parameters['domain'], $parameters['directory']
        ));
        $htaccess = sprintf(
            '%s/%s/.htaccess',
            $parameters['document_root'],
            $parameters['directory']
        );
        $contents = file_get_contents($htaccess);
        $contents = preg_replace('#php_#', '# php_', $contents);
        $contents = preg_replace('#Options#', '# Options', $contents);
        $contents = preg_replace(
            '#RewriteEngine on#',
            sprintf("RewriteEngine on\nRewriteBase %s", $parse_url['path']),
            $contents
        );
        file_put_contents($htaccess, $contents);

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        $curl->request(
            sprintf(
                'http://%s/%s/install/index.php',
                $parameters['domain'],
                $parameters['directory']
            ),
            'POST',
            array(
                'action' => 'preInstall',
            ),
            array(),
            array()
        );

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        $curl->request(
            sprintf(
                'http://%s/%s/install/index.php',
                $parameters['domain'],
                $parameters['directory']
            ),
            'POST',
            array(
                'action' => 'step1',
            ),
            array(),
            array()
        );

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        $curl->request(
            sprintf(
                'http://%s/%s/install/index.php',
                $parameters['domain'],
                $parameters['directory']
            ),
            'POST',
            array(
                'action' => 'step2',
                'dir_composite' => '/usr/bin/composite',
                'dir_convert' => '/usr/bin/convert',
                'dir_mogrify' => '/usr/bin/mogrify',
                'dir_php' => '/usr/bin/php',
                'dir_root' => sprintf(
                    '%s/%s/',
                    $parameters['document_root'],
                    $parameters['directory']
                ),
                'site_url' => sprintf(
                    'http://%s/%s/',
                    $parameters['domain'],
                    $parameters['directory']
                ),
            ),
            array(),
            array()
        );

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        $curl->request(
            sprintf(
                'http://%s/%s/install/index.php',
                $parameters['domain'],
                $parameters['directory']
            ),
            'POST',
            array(
                'action' => 'step3',
                'db_host' => $parameters['mysql_hostname'],
                'db_name' => $parameters['mysql_database'],
                'db_password' => $parameters['mysql_password'],
                'db_port' => '3306',
                'db_sock' => '',
                'db_user' => $parameters['mysql_username'],
                'dir_composite' => '/usr/bin/composite',
                'dir_convert' => '/usr/bin/convert',
                'dir_mogrify' => '/usr/bin/mogrify',
                'dir_php' => '/usr/bin/php',
                'dir_root' => sprintf(
                    '%s/%s/',
                    $parameters['document_root'],
                    $parameters['directory']
                ),
                'site_url' => sprintf(
                    'http://%s/%s/',
                    $parameters['domain'],
                    $parameters['directory']
                ),
                'sql_file' => './sql/v71.sql',
            ),
            array(),
            array()
        );

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        $curl->request(
            sprintf(
                'http://%s/%s/install/index.php',
                $parameters['domain'],
                $parameters['directory']
            ),
            'POST',
            array(
                'action' => 'step4',
                'admin_password' => $parameters['admin_password'],
                'admin_username' => $parameters['admin_username'],
                'bug_report_email' => $parameters['bug_report_email'],
                'db_host' => $parameters['mysql_hostname'],
                'db_name' => $parameters['mysql_database'],
                'db_password' => $parameters['mysql_password'],
                'db_port' => '3306',
                'db_sock' => '',
                'db_user' => $parameters['mysql_username'],
                'dir_composite' => '/usr/bin/composite',
                'dir_convert' => '/usr/bin/convert',
                'dir_mogrify' => '/usr/bin/mogrify',
                'dir_php' => '/usr/bin/php',
                'dir_root' => sprintf(
                    '%s/%s/',
                    $parameters['document_root'],
                    $parameters['directory']
                ),
                'notify_email' => $parameters['notify_email'],
                'site_desc' => $parameters['site_desc'],
                'site_email' => $parameters['site_email'],
                'site_title' => $parameters['site_title'],
                'site_url' => sprintf(
                    'http://%s/%s/',
                    $parameters['domain'],
                    $parameters['directory']
                ),
                'sql_file' => './sql/v71.sql',
            ),
            array(),
            array()
        );

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        $curl->request(
            sprintf(
                'http://%s/%s/install/index.php',
                $parameters['domain'],
                $parameters['directory']
            ),
            'POST',
            array(
                'action' => 'step5',
            ),
            array(),
            array()
        );

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        $curl->request(
            sprintf(
                'http://%s/%s/install/index.php',
                $parameters['domain'],
                $parameters['directory']
            ),
            'POST',
            array(
                'action' => 'step6',
            ),
            array(),
            array()
        );

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        $curl->request(
            sprintf(
                'http://%s/%s/install/index.php',
                $parameters['domain'],
                $parameters['directory']
            ),
            'GET',
            array(),
            array(),
            array()
        );

        log_('DEBUG', sprintf(_('Step %d'), ++$step));
        list(
            $curl_exec, $curl_errno, $curl_error, $curl_getinfo
        ) = $curl->request(
            sprintf(
                'http://%s/%s/misc/install/index.php?menu=5&LANGUAGE=en',
                $parameters['domain'],
                $parameters['directory']
            ),
            'GET',
            array(),
            array(),
            array()
        );
        if (strpos($curl_exec[1], 'Installation was successful') !== false) {
            log_('DEBUG', sprintf(_('Step %d'), ++$step));
            $operating_system->rm(sprintf(
                '%s/%s/install.php',
                $parameters['document_root'],
                $parameters['directory']
            ), false);

            log_('DEBUG', 'Success');

            return parent::install($parameters);
        }
        log_('DEBUG', 'Failure');

        return false;
    }

    public function getCategory()
    {
        return _('Social Networking');
    }

    public function getDescription()
    {
        return _('The world\'s most advanced software platform for building vibrant community websites.');
    }

    public function getDetails($parameters)
    {
        $header_inc_php = sprintf(
            '%s/%s/flash/modules/global/inc/header.inc.php',
            $parameters['document_root'],
            $parameters['directory']
        );
        if (!is_file($header_inc_php)) {
            return false;
        }
        $contents = file_get_contents($header_inc_php);
        preg_match('#"VERSION",\s*"([\d+\.]+)"#', $contents, $version);

        return array(
            'version' => $version[1],
        );
    }

    public function getForm()
    {
        $control_panel = Zend_Registry::get('control_panel');
        $form = new Netenberg_Form();
        $form->addElement('select', 'domain', array(
            'label' => _('Domain'),
            'multiOptions' => $control_panel->getDomains(),
            'required' => true,
        ));
        $form->addElement('text', 'directory', array(
            'description' => _('Leave this field empty if you want to install in the web root for the domain you\'ve selected (i.e., http://domain.com/ ). If you\'d like to install in a subdirectory, please enter the path to the directory relative to the web root for your domain.  The final destination subdirectory should not exist, but all others can exist (e.g., http://domain.com/some/sub/directory - In this case, "directory" should not already exist).'),
            'filters' => array(new Netenberg_Filter_Directory()),
            'label' => _('Directory'),
            'validators' => array(new Netenberg_Validate_Directory()),
        ));
        $form->addElement('text', 'admin_username', array(
            'label' => _('Username'),
            'required' => true,
        ));
        $form->addElement('password', 'admin_password', array(
            'label' => _('Password'),
            'required' => true,
        ));
        $form->addElement('password', 'admin_password_', array(
            'label' => _('Password (Repeat)'),
            'required' => true,
            'validators' => array(
                array('Identical', false, array(
                    'token' => 'admin_password',
                )),
            ),
        ));
        $form->addElement('text', 'site_email', array(
            'label' => _('Email'),
            'required' => true,
            'validators' => array(
                array('EmailAddress', false),
            ),
        ));
        $form->addElement('text', 'site_title', array(
            'label' => _('Site Title'),
            'required' => true,
        ));
        $form->addElement('text', 'site_desc', array(
            'label' => _('Site Description'),
            'required' => true,
        ));
        $form->addElement('text', 'bug_report_email', array(
            'label' => _('Site Email'),
            'required' => true,
            'validators' => array(
                array('EmailAddress', false),
            ),
        ));
        $form->addElement('text', 'notify_email', array(
            'label' => _('Notify Email'),
            'required' => true,
            'validators' => array(
                array('EmailAddress', false),
            ),
        ));
        $form->addElement('button', 'submit');
        $form->addElement('button', 'reset');
        $form->addDisplayGroup(
            array('domain', 'directory'),
            'location_details',
            array(
                'decorators' => $form->getDefaultGroupDecorator(),
                'disableLoadDefaultDecorators' => true,
                'legend' => _('Location Details'),
            )
        );
        $form->addDisplayGroup(
            array(
                'admin_username',
                'admin_password',
                'admin_password_',
                'site_email',
            ),
            'administrator_details',
            array(
                'decorators' => $form->getDefaultGroupDecorator(),
                'disableLoadDefaultDecorators' => true,
                'legend' => _('Administrator Details'),
            )
        );
        $form->addDisplayGroup(
            array(
                'site_title',
                'site_desc',
                'bug_report_email',
                'notify_email',
            ),
            'other_details',
            array(
                'decorators' => $form->getDefaultGroupDecorator(),
                'disableLoadDefaultDecorators' => true,
                'legend' => _('Other Details'),
            )
        );
        $form->addDisplayGroup(
            array('submit', 'reset'),
            'buttons',
            array(
                'decorators' => $form->getButtonGroupDecorator(),
                'disableLoadDefaultDecorators' => true,
            )
        );

        return $form;
    }

    public function getImage()
    {
        return 'http://www.boonex.com/modules/Txt/layout/default/img/txt-d-dolphin.png';
    }

    public function getName()
    {
        return 'Dolphin';
    }

    public function getItems($parameters)
    {
        return array(
            _('Backend') => array(
                sprintf(
                    '<a href="http://%s/%s/member.php" target="_blank">http://%s/%s/member.php</a>',
                    $parameters['domain'],
                    $parameters['directory'],
                    $parameters['domain'],
                    $parameters['directory']
                ),
                sprintf(_('Username: %s'), $parameters['admin_username']),
                sprintf(_('Password: %s'), $parameters['admin_password']),
            ),
            _('Frontend') => array(
                sprintf(
                    '<a href="http://%s/%s" target="_blank">http://%s/%s</a>',
                    $parameters['domain'],
                    $parameters['directory'],
                    $parameters['domain'],
                    $parameters['directory']
                ),
            ),
        );
    }

    public function getRequirements()
    {
        $control_panel = Zend_Registry::get('control_panel');
        $apache = $control_panel->getApache();
        $mysql = $control_panel->getMySQL();
        $php = $control_panel->getPHP();

        preg_match('#memory_limit\s*=>\s*([^= ]*)#', $php, $matches);
        if (!empty($matches[1])) {
            $value = getDecodedSize($matches[1]);
        } else {
            $value = -1;
        }

        return array(
            'Disk Space' => (
                $control_panel->getSize() >= $this->getSize()
            )? true: false,
            'Apache 1+' => (
                strpos($apache, 'Apache/1') !== false
                or
                strpos($apache, 'Apache/2') !== false
            )? true: false,
            'Apache :: mod_rewrite' => true,
            'MySQL 4.1+' => (
                strpos($mysql, 'Distrib 4.1') !== false
                or
                strpos($mysql, 'Distrib 5') !== false
            )? true: false,
            'PHP 5.2.0+' => (preg_match(
                '#PHP Version\s*=>\s*(5\.[2-4])#', $php
            ) === 1)? true: false,
            'PHP :: gd' => (preg_match(
                '#GD Support\s*=>\s*enabled#', $php
            ) === 1)? true: false,
            'PHP :: curl' => (preg_match(
                '#cURL support\s*=>\s*enabled#', $php
            ) === 1)? true: false,
            'PHP :: mbstring' => (preg_match(
                '#Multibyte Support\s*=>\s*enabled#', $php
            ) === 1)? true: false,
            'PHP :: mysql' => (preg_match(
                '#MySQL Support\s*=>\s*enabled#', $php
            ) === 1)? true: false,
            'PHP :: mysqli' => (preg_match(
                '#MysqlI Support\s*=>\s*enabled#', $php
            ) === 1)? true: false,
            'PHP :: safe_mode = Off' => (preg_match(
                '#^(safe_mode\s*=>\s*On|safe_mode\s*=>\s*on|safe_mode\s*=>\s*1)#',
                $php
            ) === 0)? true: false,
            'PHP :: short_open_tag = On' => (preg_match(
                '#^(short_open_tag\s*=>\s*Off|short_open_tag\s*=>\s*off|short_open_tag\s*=>\s*0)#',
                $php
            ) === 0)? true: false,
            'PHP :: allow_url_fopen = On' => (preg_match(
                '#^(allow_url_fopen\s*=>\s*Off|allow_url_fopen\s*=>\s*off|allow_url_fopen\s*=>\s*0)#',
                $php
            ) === 0)? true: false,
            'PHP :: allow_url_include = Off' => (preg_match(
                '#^(allow_url_include\s*=>\s*Off|allow_url_include\s*=>\s*off|allow_url_include\s*=>\s*0)#',
                $php
            ) === 0)? true: false,
            'PHP :: open_basedir = \'\'' => (preg_match(
                '#(open_basedir\s*=>\s*\'\'|open_basedir\s*=>\s*no value)#',
                $php
            ) === 1)? true: false,
            'PHP :: memory_limit' => (
                $value == -1 or $value >= (128 * 1024 * 1024)
            )? true: false,
        );
    }

    public function getSize()
    {
        return 44983910;
    }

    public function getSlug()
    {
        return 'dolphin';
    }

    public function getUrls()
    {
        return array(
            _('Home') => 'http://www.boonex.com/dolphin',
            _('Support') => 'http://www.boonex.com/forums',
        );
    }

    public function getVersion()
    {
        return '7.1.4';
    }

    public function uninstall($parameters)
    {
        $control_panel = Zend_Registry::get('control_panel');
        $operating_system = Zend_Registry::get('operating_system');

        $header_inc_php = sprintf(
            '%s/%s/inc/header.inc.php',
            $parameters['document_root'],
            $parameters['directory']
        );
        if (!is_file($header_inc_php)) {
            return false;
        }
        $contents = file_get_contents($header_inc_php);
        preg_match('#db\[\'db\'\]\s*=\s*\'([^\']*)#', $contents, $database);
        preg_match(
            '#db\[\'user\'\]\s*=\s*\'([^\']*)#', $contents, $mysql_username
        );
        $control_panel->deleteMySQL($mysql_username[1], $database[1]);

        $operating_system->dispose(sprintf(
            '%s/%s', $parameters['document_root'], $parameters['directory']
        ));

        return parent::uninstall($parameters);
    }
}
