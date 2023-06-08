<?php
    namespace pw2s3\clinicaveterinaria\model\auth;

    use DateTimeImmutable;
    use InvalidArgumentException;

    final class Token {
        private const MAX_AGE_IN_MINUTES = "60 minutes";
        private readonly DateTimeImmutable $issueDateTime;
        private readonly string $issuer;
        private readonly DateTimeImmutable $notBeforeDateTime;
        private readonly DateTimeImmutable $expiringDateTime;
        private readonly int $subject;
        private array $data = [];

        public function __construct(DateTimeImmutable $issuedAt, string $issuer, int $subject, 
                                    ?DateTimeImmutable $notBefore=null) {
            $this->setIssueDateTime($issuedAt);
            $this->issuer = $issuer;
            $this->subject = $subject;
            
            if ($notBefore != null) $this->setNotBeforeDateTime($notBefore);
        }

        public function addData(string $fieldName, string $data) : void {
            $this->data[$fieldName] = $data;
        }

        public function getIssueDateTime() : DateTimeImmutable {
            return $this->issueDateTime;
        }

        public function setIssueDateTime(DateTimeImmutable $issueDateTime) : void {
            $this->issueDateTime = $issueDateTime;
            $this->notBeforeDateTime = $issueDateTime;
            $this->expiringDateTime = $issueDateTime->modify("+ " . self::MAX_AGE_IN_MINUTES);
        }

        public function getNotBeforeDateTime() : DateTimeImmutable {
            return $this->notBeforeDateTime;
        }

        public function setNotBeforeDateTime(DateTimeImmutable $notBeforeDateTime) : void {
            if ($notBeforeDateTime < $this->issueDateTime || $notBeforeDateTime >= $this->expiringDateTime)
                throw new InvalidArgumentException("The not-before date time must be after when this was issued and " .
                                "before the expiring date time!");
            $this->notBeforeDateTime = $notBeforeDateTime;
        }

        public function getExpiringDateTime() : DateTimeImmutable {
            return $this->expiringDateTime;
        }

        public function getIssuer() : string {
            return $this->issuer;
        }

        public function getSubject() : int {
            return $this->subject;
        }

        public function getData() : array {
            return array_slice($this->data, 0);
        }
    }
?>
