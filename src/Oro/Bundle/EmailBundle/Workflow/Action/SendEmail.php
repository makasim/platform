<?php

namespace Oro\Bundle\EmailBundle\Workflow\Action;

use Oro\Bundle\EmailBundle\Entity\Util\EmailUtil;
use Oro\Bundle\EmailBundle\Form\Model\Email;
use Oro\Bundle\EmailBundle\Mailer\Processor;
use Oro\Bundle\LocaleBundle\Formatter\NameFormatter;
use Oro\Bundle\WorkflowBundle\Exception\InvalidParameterException;
use Oro\Bundle\WorkflowBundle\Model\Action\AbstractAction;
use Oro\Bundle\WorkflowBundle\Model\ContextAccessor;

class SendEmail extends AbstractAction
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var Processor
     */
    protected $emailProcessor;

    /**
     * @var NameFormatter
     */
    protected $nameFormatter;

    /**
     * @param ContextAccessor $contextAccessor
     * @param Processor $emailProcessor
     * @param NameFormatter $nameFormatter
     */
    public function __construct(
        ContextAccessor $contextAccessor,
        Processor $emailProcessor,
        NameFormatter $nameFormatter
    ) {
        parent::__construct($contextAccessor);

        $this->emailProcessor = $emailProcessor;
        $this->nameFormatter = $nameFormatter;
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(array $options)
    {
        if (empty($options['from'])) {
            throw new InvalidParameterException('From parameter is required');
        }
        if (is_array($options['from']) && !array_key_exists('email', $options['from'])) {
            throw new InvalidParameterException('From email parameter is required');
        }

        if (empty($options['to'])) {
            throw new InvalidParameterException('To parameter is required');
        }
        if (is_array($options['to']) && !array_key_exists('email', $options['to'])) {
            throw new InvalidParameterException('To email parameter is required');
        }

        if (empty($options['subject'])) {
            throw new InvalidParameterException('Subject parameter is required');
        }
        if (empty($options['body'])) {
            throw new InvalidParameterException('Body parameter is required');
        }

        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    protected function executeAction($context)
    {
        $email = new Email();
        $email->setFrom($this->getEmailAddress($context, $this->options['from']));
        $email->setTo(
            array($this->getEmailAddress($context, $this->options['to']))
        );
        $email->setSubject(
            $this->contextAccessor->getValue($context, $this->options['subject'])
        );
        $email->setBody(
            $this->contextAccessor->getValue($context, $this->options['body'])
        );

        $this->emailProcessor->process($email);
    }

    /**
     * Get email address prepared for sending.
     *
     * @param mixed $context
     * @param string|array $data
     * @return string
     */
    protected function getEmailAddress($context, $data)
    {
        $name = null;
        $emailAddress = $this->contextAccessor->getValue($context, $data);

        if (is_array($data)) {
            $emailAddress = $this->contextAccessor->getValue($context, $data['email']);

            if (array_key_exists('name', $data)) {
                $data['name'] = $this->contextAccessor->getValue($context, $data['name']);

                if (is_object($data['name'])) {
                    $name = $this->nameFormatter->format($data['name']);
                } else {
                    $name = $data['name'];
                }
            }
        }

        return EmailUtil::buildFullEmailAddress($emailAddress, $name);
    }
}
