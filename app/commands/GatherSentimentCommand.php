<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Carbon\Carbon;
require base_path() . '/vendor/alchemyai/alchemyapi_php/alchemyapi.php';

class GatherSentimentCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'ii:gather-sentiment';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Grabs a set of tweets for each company, processes them through the Alchemy sentiment-analysis API, and then stores the processed tweets to the processed_tweets table';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		// the trial api only allows 500 tweets per day, so limit our data set unless overriden in config.
		$sentimentApiLimit = isset($_ENV['SENTIMENT_API_DAILY_LIMIT']) ? $_ENV['SENTIMENT_API_DAILY_LIMIT'] : 500;

		// Make sure laravel cwd is in the base project folder.
		// The api_key.txt file required by the AlchemyAPI is here and will be loaded properly.
		chdir(base_path());

		$alchemy = new AlchemyAPI();

		// Process tweets for all companies, chunking to avoid out of memory errors.
		Candidate::chunk(100, function($companies) use ($alchemy, $sentimentApiLimit)
		{
			// track calls to the sentiment api.
			$sentimentApiCalls = 0;

		    foreach ($companies as $company)
		    {
		    	// look for the companies twitter handle in the tweets
		        $searchQuery = '@' . $company->twitter_handle;
				$tweetsToProcess = RawTweet::where('text', 'like', "%$searchQuery%")->take($sentimentApiLimit)->get();

				foreach ($tweetsToProcess as $tweetToProcess)
				{
					$response = $alchemy->sentiment('text', $tweetToProcess['text'], array('sentiment' => 1));
                    Log::debug($response);
					$sentimentApiCalls++;

					if (isset($response['status']) && isset($response['docSentiment']['type']) && $response['status'] == 'OK')
					{
						ProcessedTweet::create([
							'text' => $tweetToProcess['text'],
							'tweeted_at' => Carbon::createFromTimeStamp($tweetToProcess['timestamp'] / 1000)->toDateTimeString(),
							'candidate_id' => $company->id,
							'type' => $response['docSentiment']['type'],
							'sentiment' => $response['docSentiment']['type'] == 'neutral' ? 0 : $response['docSentiment']['score']
						]);
					}

					// remove the tweet that has been processed.
					$tweetToProcess->delete();
				}

				$sentimentApiLimit -= $sentimentApiCalls;

				if ($sentimentApiLimit <= 0)
				{
					// stop processing when we have used up our call limit.
					return;
				}
		    }
		});
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	/*protected function getArguments()
	{
		return array(
			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}*/

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	/*protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}*/

}
