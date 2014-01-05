<?php

class Link extends Model {
	//woo variables
	private $ip = "";
	private $hash = "";
	private $url = "";
	
	/*===============================
	 * string[8] - generateHash()
	 * - generates random hash string
	 */
	private function generateHash()
	{
		$random_string = "";
		$valid_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$length = 8;

	    // count the number of chars in the valid chars string so we know how many choices we have
	    $num_valid_chars = strlen($valid_chars);
	
	    // repeat the steps until we've created a string of the right length
	    for ($i = 0; $i < $length; $i++)
	    {
	        // pick a random number from 1 up to the number of valid chars
	        $random_pick = mt_rand(1, $num_valid_chars);
	
	        // take the random character out of the string of valid chars
	        // subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
	        $random_char = $valid_chars[$random_pick-1];
	
	        // add the randomly-chosen char onto the end of our string so far
	        $random_string .= $random_char;
	    }
	
	    // return our finished random string
	    return $random_string;
	}
	
	/*===============================
	 * new Link - fromURL (string url)
	 * 
	 * - checks to see if url is hashed or not
	 * - returns new object of url if url is prehashed
	 * - generates hash and enters into database it not
	 * - returns new instance
	 * - returns false on failure
	 */
	public function fromURL($url)
	{
		$url = $this->escapeString($url);
		
		if(substr($url, 0, 7) != 'http://') { 
			if(substr($url, 0, 8) != 'https://') { 
				$url = 'http://' . $url; 
			} 
		}
		$url = rtrim($url, '/');
		
		//see if we already have a hash
		$result = $this->query('SELECT ip, hash FROM urls WHERE url="'. $url .'";');
		
		if (isset($result[0])) {
			//so a hash already exists for this url
			//set our variables from our found record
			$this->ip = stripslashes($result[0]->ip);
			$this->hash = stripslashes($result[0]->hash);
			$this->url = $url;
			
			//pass on our reference
			return $this;
		} else {
			//looks like we have to make a new hash and enter it into the db
			$this->ip = $_SERVER['REMOTE_ADDR'];
			$this->url = $url;
			
			//try and insert with a new hash
			$this->hash = $this->generateHash();
			//loop until we have a new one
			while ($this->hashExists($this->hash)) {
				//make a new hash if that one already exists
				$this->hash = $this->generateHash();
			}
			
			//so we have a hash that doesn't exist
			//let's insert this into the DB
			$result = mysql_query('INSERT INTO urls (
				`hash`, 
				`ip`, 
				`url`
			) VALUES (
				"'. $this->hash .'", 
				"'. $this->ip .'", 
				"'. $this->url .'"
			);') or die('MySQL Error: '. mysql_error());
			//did we insert?
			if (!$result) return false;
			
			//pass on our reference
			return $this;
		}
	}
	
	/*==============================
	 * boolean - hashExists(string hash)
	 * 
	 * - returns whether the hash exists in the database
	 */
	public function hashExists($hash) 
	{
		//check if the has exists
		$hash = $this->escapeString($hash);
		//see if we already have a hash
		$result = $this->query('SELECT id FROM urls WHERE hash="'. $hash .'";');
		return isset($result[0]);
	}
	
	/*===============================
	 * new Link - fromHash(string hash)
	 * 
	 * - attempts to find object form hashed url
	 * - returns object if found
	 * - returns false if no hash exists
	 */
	public function fromHash($hash)
	{
		$hash = $this->escapeString($hash);
		//see if we already have a hash
		$result = $this->query('SELECT ip, url FROM urls WHERE hash="'. $hash .'";');
		if (isset($result[0])) {
			$this->ip = stripslashes($result[0]->ip);
			$this->url = stripslashes($result[0]->url);
			$this->hash = $hash;
			return $this;
		} else 
			return false;
	}
	
	//Standard getters and setters
	public function getURL()
	{
		return $this->url != '' ? $this->url : false;
	}
	
	public function getHash()
	{
		return $this->hash != '' ? $this->hash : false;
	}
}

?>
