<?php


class EmailMessage
{
    private $to;
    private array $headers;
    private string $subject;
    private string $body;

    /**
     * @return string|array|null
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string|array|null
     */
    public function setTo($to): void
    {
        $this->to = $to;
    }

    /**
     * @return string|null
     */
    public function getFrom(): ?string
    {
        foreach ($this->headers as $header) {
            if (preg_match('/From: (.+)/i', $header, $matches) && $matches[1]) {
                return $matches[1];
            }
        }
        return null;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @return string|null
     */
    public function getBcc(): ?string
    {
        foreach ($this->headers as $header) {
            if (preg_match('/BCC: (.+)/i', $header, $matches) && $matches[1]) {
                return $matches[1];
            }
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function getCc(): ?string
    {
        foreach ($this->headers as $header) {
            if (preg_match('/CC: (.+)/i', $header, $matches) && $matches[1]) {
                return $matches[1];
            }
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function getContentType(): ?string
    {
        foreach ($this->headers as $header) {
            if (preg_match('/Content-Type: (.+)/i', $header, $matches) && $matches[1]) {
                return $matches[1];
            }
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function getReplyTo(): ?string
    {
        foreach ($this->headers as $header) {
            if (preg_match('/Reply-To: (.+)/i', $header, $matches) && $matches[1]) {
                return $matches[1];
            }
        }
        return null;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }
}
