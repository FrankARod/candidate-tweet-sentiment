<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CollectTweets extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'ii:collect-tweets';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

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
		$time = time();
		Log::info('Tweet collection started on ' . $time);
		
		// company handles to track
		$twitterHandles = TwitterStreamHandler::buildKeywordList();

		// save the latest keyword list
		TwitterStreamHandler::saveKeywords($twitterHandles);

		// The OAuth credentials you received when registering your app at Twitter
		define("TWITTER_CONSUMER_KEY", $_ENV['CONSUMER-KEY']);
		define("TWITTER_CONSUMER_SECRET", $_ENV['CONSUMER-SECRET']);
		// The OAuth data for the twitter account
		define("OAUTH_TOKEN", $_ENV['ACCESS-TOKEN']);
		define("OAUTH_SECRET", $_ENV['ACCESS-TOKEN-SECRET']);

		Log::info('Tracking company handles: ' . implode(', ', $twitterHandles));

		// Start streaming
		$sc = new TwitterStreamHandler(OAUTH_TOKEN, OAUTH_SECRET, Phirehose::METHOD_FILTER);
		$sc->setTrack($twitterHandles);
		$sc->consume();

		Log::info('Collection started on ' . $time . ' ended');
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
