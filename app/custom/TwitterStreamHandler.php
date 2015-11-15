<?php

class TwitterStreamHandler extends OauthPhirehose {

	public static $baseUrl = 'https://api.twitter.com/1.1/';

	/**
	 * Method to save tweets as they are received from the firehose.
	 * @param  [type] $status [description]
	 * @return [type]         [description]
	 */
	public function enqueueStatus($status)
	{
		$data = json_decode($status, true);

		if (isset($data['user']['screen_name']) && isset($data['text']) && isset($data['timestamp_ms']))
		{
			$tweet = new RawTweet();
			$tweet->text = $data['text'];
			$tweet->timestamp = $data['timestamp_ms'];
			$tweet->save();
		}
	}

	/**
	 * Method to update tracking array.
	 * @return [type] [description]
	 */
	public function checkFilterPredicates()
	{
		$keywords = self::loadKeywords();
		$this->setTrack($keywords);
	}

	public static function buildKeywordList()
	{
		// company handles to track
		$candidateTwitterHandles = [];

		Candidate::chunk(100, function($candiates) use (&$candidateTwitterHandles)
		{
			foreach ($candiates as $candidate)
			{
    			$candidateTwitterHandles[] = '@' . $candidate->twitter_handle;
			}
		});

		return $candidateTwitterHandles;
	}

	public static function saveKeywords($keywordList)
	{
		$keywordFile = base_path() . '/keywords.txt';
		file_put_contents($keywordFile, implode(',', $keywordList));
	}

	public static function loadKeywords()
	{
		$keywords = array();

		$keywordFile = base_path() . '/keywords.txt';

		if (file_exists($keywordFile))
		{
			$keywords = explode(',', file_get_contents($keywordFile));
		}

		return $keywords;
	}

}