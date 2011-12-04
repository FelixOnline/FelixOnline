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

    /*
     * Constructor for Email class
     */
    public function __construct() {
        // To send HTML mail, the Content-type header must be set
        $this->headers  = 'MIME-Version: 1.0' . "\r\n";
        $this->headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $this->headers .= "From: ".EMAIL_FROM_ADDR."\r\n" .
        'Reply-To: '.EMAIL_REPLYTO_ADDR."\r\n" .
        'X-Mailer: PHP/' . phpversion();

        $this->emailFolder = 'emails/';
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
     */
    public function setContent($content) {
        $this->content = $content;
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
     * Public: Send email
     */
    public function send() {
        foreach($this->to as $key => $email) {
            if(LOCAL) { // if on local machine
                $file = $this->emailFolder.date('Y-m-d H:i:s').' '.$this->subject.' '.$key.'.txt';
                $fh = fopen($file, 'w') or die("can't open file");
                $body = 'TO: '.$email."\r\n";
                $body .= 'SUBJECT: '.$this->subject."\r\n";
                $body .= "CONTENT: \r\n";
                $body .= $this->content;
                fwrite($fh, $body);
                fclose($fh);
            } else { 
                mail($email, $this->subject, $this->content, $this->headers);
            }
        }
    }

	public function printThis() {
		print_r($this);
	}
}

?>
