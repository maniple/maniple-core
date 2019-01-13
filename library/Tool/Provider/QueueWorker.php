<?php

class ManipleCore_Tool_Provider_QueueWorker extends Maniple_Tool_Provider_Abstract
{
    /**
     * Execute queue worker
     *
     * @param int $numMessages Maximum number of messages to process
     */
    public function exec($numMessages = 1)
    {
        $n = (int) $numMessages;
        if ($n <= 0) {
            throw new Zend_Tool_Project_Provider_Exception(sprintf(
                'Invalid number of messages to process: %s', $numMessages
            ));
        }

        $application = $this->_getApplication()->bootstrap();

        /** @var ManipleCore_Queue_Service $queueService */
        $queueService = $application->getBootstrap()->getResource('Maniple.Queue');

        set_time_limit(0);

        $queueService->getEventManager()->attach('message', function (ManipleCore_Queue_MessageEvent $event) {
            echo '[message] Received message from queue: ', $event->getMessage()->getQueue()->getName(), "\n";
        });
        $queueService->process($numMessages);
    }
}
