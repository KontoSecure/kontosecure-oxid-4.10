<?php

/**
 * Class kontosecure_webhooklog
 */
class kontosecure_webhooklog extends oxI18n
{

    protected $_sCoreTbl = 'kontosecurewebhooklog';
    protected $_sClassName = 'kontosecure_webhooklog';

    /**
     * kontosecure_webhooklog constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->init($this->_sCoreTbl);
    }
}
