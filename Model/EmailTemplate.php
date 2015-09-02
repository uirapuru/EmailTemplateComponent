<?php

namespace TPN\EmailTemplatesComponent\Model;

use JMS\Serializer\Annotation as Serializer;
use TPN\EmailTemplatesComponent\Element\ElementInterface;

/**
 * @Serializer\ExclusionPolicy("all")
 */
class EmailTemplate
{
    /**
     * @var int
     * @Serializer\Expose()
     * @Serializer\Type("integer")
     */
    protected $id;

    /**
     * @var string
     * @Serializer\Expose()
     * @Serializer\Type("string")
     */
    protected $emailType;

    /**
     * @var string
     * @Serializer\Expose()
     * @Serializer\Type("string")
     */
    protected $subject;

    /**
     * @var string
     * @Serializer\Expose()
     * @Serializer\Type("array")
     */
    protected $body;

    /**
     * @var string
     * @Serializer\Expose()
     * @Serializer\Type("string")
     */
    protected $fromEmail;

    /**
     * @var string[]
     * @Serializer\Expose()
     * @Serializer\Type("array")
     */
    protected $tags;

    /**
     * @var string[]
     * @Serializer\Expose()
     * @Serializer\Type("array")
     */
    protected $blocks;

    /**
     * @var ElementInterface[]
     * @Serializer\Exclude()
     */
    protected $children;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmailType()
    {
        return $this->emailType;
    }

    /**
     * @param string $emailType
     */
    public function setEmailType($emailType)
    {
        $this->emailType = $emailType;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    /**
     * @param string $fromEmail
     */
    public function setFromEmail($fromEmail)
    {
        $this->fromEmail = $fromEmail;
    }

    /**
     * @return string[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param string[] $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return string[]
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @param string[] $blocks
     */
    public function setBlocks($blocks)
    {
        $this->blocks = $blocks;
    }

    /**
     * @return ElementInterface[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param ElementInterface[] $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function render()
    {
        if (count($this->children) === 0) {
            throw new \Exception('Template children field is empty! You have to use ParserInterface compatible parser to convert body field');
        }

        $result = '';

        foreach ($this->children as $child) {
            $result .= $child->dump();
        }

        return $result;
    }
}
