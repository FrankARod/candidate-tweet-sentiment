<?php

class Candidate extends \Eloquent {
	protected $fillable = [];

    public function tweets()
    {
        return $this->hasMany('ProcessedTweet');
    }

    public function getAverageSentimentAttribute()
    {
        return $this->tweets()->avg('sentiment');
    }
}