<?php

class ProcessedTweet extends \Eloquent {
	protected $fillable = ['candidate_id', 'text', 'sentiment', 'type', 'tweeted_at'];
}