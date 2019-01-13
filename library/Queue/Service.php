<?php

class ManipleCore_Queue_Service
{
    /**
     * @var Zend_Queue_Adapter_AdapterInterface
     */
    protected $_adapter;

    /**
     * @var Zend_Queue[]
     */
    protected $_queues;

    /**
     * @var Zend_EventManager_EventManager
     */
    protected $_events;

    /**
     * @var Zend_Log
     */
    protected $_logger;

    /**
     * @param Zefram_Db $db
     * @param Zend_EventManager_SharedEventManager $sharedEvents
     * @param Zend_Log $logger
     */
    public function __construct(Zefram_Db $db, Zend_EventManager_SharedEventManager $sharedEvents, Zend_Log $logger = null)
    {
        $this->_adapter = new ManipleCore_Queue_Adapter(array(
            'dbAdapter' => $db->getAdapter(),
            'tablePrefix' => $db->getTablePrefix(),
        ));

        $this->_events = new Zend_EventManager_EventManager();
        $this->_events->setIdentifiers(array(
            __CLASS__,
            get_class($this),
            'Maniple.Queue',
        ));
        $this->_events->setSharedCollections($sharedEvents);
        $this->_events->setEventClass(ManipleCore_Queue_MessageEvent::className);

        $this->_logger = $logger;
    }

    /**
     * @return Zend_EventManager_EventManager
     */
    public function getEventManager()
    {
        return $this->_events;
    }

    public function process($maxMessages = 1)
    {
        $queues = $this->_adapter->getQueues();

        while (--$maxMessages >= 0) {
            foreach ($queues as $queueName) {
                $queue = $this->openQueue($queueName);

                /** @var Zend_Queue $queue */
                $message = $this->_adapter->receive(1, null, $queue)->current();

                if ($message === null) {
                    if ($this->_logger) {
                        $this->_logger->info(sprintf("Queue %s is empty", $queueName));
                    }
                    continue;
                }

                $event = new ManipleCore_Queue_MessageEvent();
                $event->setMessage($message);

                if ($this->_logger) {
                    $this->_logger->info(sprintf("Received message from queue %s", $queueName));
                }

                $this->_events->trigger("message.{$queue->getName()}", $this, $event);
                $this->_events->trigger('message', $this, $event);
            }
        }
    }

    /**
     * @param string $name
     * @return Zend_Queue
     */
    public function openQueue($name)
    {
        if (empty($this->_queues[$name])) {
            $this->_adapter->create($name);
            $this->_queues[$name] = new Zend_Queue($this->_adapter, array('name' => $name));
        }
        return $this->_queues[$name];
    }
}
