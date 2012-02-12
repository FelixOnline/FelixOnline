<?php
/*
 * Email class
 *
 * Deals with all emailing from Felix Online
 *
 * Examples:
 *      $email = new Email();
 *      $email->setTo('felix@imperial.ac.uk');
 *      $email->setSubject('Test email');
 *      $email->setContent('I love you!');
 *      if($email->send()) echo 'Email sent!';
 *
 */
class Email {
    private $to = array(); // array of email address to send email to
    private $subject; // subject of email
    private $content; // html content of email
    private $headers; // headers for email
    private $emailFolder; // folder to store emails in
    private $uniqueid; // unique id for email. Used when logging emails to files
    private $from;
    private $replyto;

    /*
     * Constructor for Email class
     */
    public function __construct() {
        // To send HTML mail, the Content-type header must be set
        $this->headers  = 'MIME-Version: 1.0' . "\r\n";
        $this->headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $this->headers .= 'X-Mailer: PHP/' . phpversion()."\r\n";
        $this->from = "From: ".EMAIL_FROM_ADDR."\r\n";
        $this->replyto = 'Reply-To: '.EMAIL_REPLYTO_ADDR."\r\n";

        $this->emailFolder = BASE_DIRECTORY.'/emails/';
		
		if(!is_writable($this->emailFolder)) {
			throw new InternalException('Email storage directory '.$this->emailFolder.' is not writable');
		}
    }

    /*
     * Public: Set email address
     *
     * $to - either an array or address or a single address
     *
     * Return TODO
     */
    public function setTo($to) {
        if(is_array($to)) { // if to is an array
            $this->to = $to;
        } else if(is_string($to)) { // if single value
            $this->to[] = $to; // add single address to to array
        }
    }

    /*
     * Public: Set subject of email
     *
     * $subject - subject string
     */
    public function setSubject($subject) {
        $this->subject = $subject;
    }

    /*
     * Public: Set content of email
     *
     * $content - content string (html)
     *
     * Returns content
     */
    public function setContent($content) {
        $this->content = $content;
        return $this->content;
    }

    /*
     * Public: Prepend content of email
     * Useful for adding welcome messages etc.
     *
     * $message - html string of message
     *
     * Returns new message content
     */
    public function prependContent($message) {
        $this->content = $message . $this->content;
        return $this->content;
    }

    /*
     * Public: Append content of email
     * Add message to the end of an email
     *
     * $message - html string of message
     *
     * Returns new message content
     */
    public function appendContent($message) {
        $this->content = $this->content . $message;
        return $this->content;
    }

    /*
     * Public: Set headers for email
     *
     * $headers - string for headers
     */
    public function setHeaders($headers) {
        $this->headers = $headers;
    }

    /*
     * Public: Set from address
     */
    public function setFrom($email, $name=NULL) {
        if($name == NULL) {
            $this->from = 'From: '.$email. "\r\n";
        } else {
            $this->from = 'From: '.$name.' <'.$email.'>' . "\r\n";
        }
        return $this->from;
    }

    /*
     * Public: Set reply to address
     */
    public function setReplyTo($email) {
        $this->replyto = 'Reply-To: '.$email."\r\n";
        return $this->replyto;
    }

    public function setUniqueID($id) {
        $this->uniqueid = $id;
    }

    /*
     * Public: Get email headers
     */
    public function getHeaders() {
        $output = $this->headers;
        $output .= $this->from;
        $output .= $this->replyto;
        return $output;
    }

    /*
     * Public: Send email
     */
    public function send() {
        foreach($this->to as $key => $email) {
            if(LOCAL) { // if on local machine
                $this->logEmail($key, $email);
            } else { 
                mail(
                    $email, 
                    $this->subject, 
                    $this->content, 
                    $this->getHeaders()
                );
            }

            /* if logging emails */
            if(LOG_EMAILS == true && !LOCAL) {
                $this->logEmail($key, $email);
            }
        }
    }

    /*
     * Private: Log email into file
     *
     * $key - identifier for email when sending multiple
     * $email - email address
     */
    private function logEmail($key, $email) {
        $file = $this->emailFolder.date('Y-m-d H:i:s').' '.$this->subject.' '.$key.' '.$this->uniqueid.'.txt';
        $fh = fopen($file, 'w');
        $body = 'TO: '.$email."\r\n";
        $body .= 'SUBJECT: '.$this->subject."\r\n";
        $body .= 'HEADERS: '.$this->getHeaders()."\r\n";
        $body .= "CONTENT: \r\n";
        $body .= $this->content;
        fwrite($fh, $body);
        fclose($fh);
    }

	public function printThis() {
		print_r($this);
	}
}

?>
