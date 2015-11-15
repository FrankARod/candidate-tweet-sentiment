<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$candidate = new Candidate();
        $candidate->name = 'Marco Rubio';
        $candidate->twitter_handle = 'marcorubio';
        $candidate->save();

        $candidate = new Candidate();
        $candidate->name = 'Ted Cruz';
        $candidate->twitter_handle = 'tedcruz';
        $candidate->save();

        $candidate = new Candidate();
        $candidate->name = 'Ben Carson';
        $candidate->twitter_handle = 'RealBenCarson';
        $candidate->save();

        $candidate = new Candidate();
        $candidate->name = 'Jeb Bush';
        $candidate->twitter_handle = 'JebBush';
        $candidate->save();

        $candidate = new Candidate();
        $candidate->name = 'Donald Trump';
        $candidate->twitter_handle = 'realDonaldTrump';
        $candidate->save();

        $candidate = new Candidate();
        $candidate->name = 'Carly Fiorina';
        $candidate->twitter_handle = 'CarlyFiorina';
        $candidate->save();

        $candidate = new Candidate();
        $candidate->name = 'John Kasich';
        $candidate->twitter_handle = 'JohnKasich';
        $candidate->save();

        $candidate = new Candidate();
        $candidate->name = 'Rand Paul';
        $candidate->twitter_handle = 'RandPaul';
        $candidate->save();

        $candidate = new Candidate();
        $candidate->name = 'Bernie Sanders';
        $candidate->twitter_handle = 'berniesanders';
        $candidate->save();

        $candidate = new Candidate();
        $candidate->name = 'Hillary Clinton';
        $candidate->twitter_handle = 'HillaryClinton';
        $candidate->save();

        $candidate = new Candidate();
        $candidate->name = 'Martin O\'Malley';
        $candidate->twitter_handle = 'martinomalley';
        $candidate->save();
	}

}
