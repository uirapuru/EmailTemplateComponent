<?php

namespace TPN\EmailTemplatesComponent\Model\Factory;

use JMS\Serializer\SerializerInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use TPN\EmailTemplatesComponent\ArrayConverterInterface;
use TPN\EmailTemplatesComponent\ValidatorInterface;
use TPN\EmailTemplatesComponent\Model\EmailTemplate;

/**
 * Class EmailTemplateFactory
 * @package TPN\EmailTemplatesComponent\Model\Factory
 */
class EmailTemplateFactory
{
    /**
     * @var ArrayConverterInterface
     */
    private $arrayConverter;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param SerializerInterface     $serializer
     * @param ArrayConverterInterface $arrayConverter
     * @param ValidatorInterface      $validator
     * @param LoggerInterface         $logger
     */
    public function __construct(SerializerInterface $serializer, ArrayConverterInterface $arrayConverter, ValidatorInterface $validator, LoggerInterface $logger)
    {
        $this->serializer = $serializer;
        $this->arrayConverter = $arrayConverter;
        $this->validator = $validator;
        $this->logger = $logger;
    }

    /**
     * @return EmailTemplate
     */
    public function create()
    {
        return new EmailTemplate();
    }

    /**
     * @param $jsonString
     *
     * @return EmailTemplate
     */
    public function createFromJson($jsonString)
    {
        /*
         * @var EmailTemplate
         */
        $template = $this->serializer->deserialize($jsonString, 'TPN\EmailTemplatesComponent\Model\EmailTemplate', 'json');
        $template->setChildren($this->arrayConverter->createFromArray($template->getBody()));
        $this->validateTemplate($template);
        $template->setBody(
            $template->render()
        );

        return $template;
    }

    /**
     * @param $jsonString
     *
     * @return EmailTemplate[]
     */
    public function createFromJsonCollection($jsonString)
    {
        /*
         * @var EmailTemplate[]
         */
        $templates = $this->serializer->deserialize($jsonString, 'array<TPN\EmailTemplatesComponent\Model\EmailTemplate>', 'json');

        foreach ($templates as $template) {
            $template->setChildren($this->arrayConverter->createFromArray($template->getBody()));
            $this->validateTemplate($template);
            $template->setBody(
                $template->render()
            );
        }

        return $templates;
    }

    /**
     * @param EmailTemplate $template
     *
     * @throws \Exception
     */
    private function validateTemplate(EmailTemplate $template)
    {
        /*
         * @var ErrorTrace
         */
        $errorTrace = $this->validator->validate($template);

        if ($errorTrace->hasErrors()) {
            $this->logger->log(Logger::CRITICAL, $errorTrace->dump());
            throw new \Exception(
                sprintf('Error when validating requested email template. Validator dump: %s', $errorTrace->dump())
            );
        }
    }
}
