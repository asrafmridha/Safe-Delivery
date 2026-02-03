<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // $this->call(AdminSeeder::class);
        // $this->call(ApplicationSeeder::class);
        // $this->call(SliderSeeder::class);
        // $this->call(DesignationSeeder::class);
        // $this->call(TeamMemberSeeder::class);
        // $this->call(PartnerSeeder::class);
        // $this->call(FrequentlyAskQuestionSeeder::class);
        // $this->call(ServiceSeeder::class);
        // $this->call(BlogSeeder::class);
        // $this->call(SocialLinkSeeder::class);
        // $this->call(ParcelStepSeeder::class);
        // $this->call(AboutPointSeeder::class);
        // $this->call(PageContentSeeder::class);
        // $this->call(VisitorMessageSeeder::class);
        // $this->call(NewsLetterSeeder::class);
        // $this->call(CustomerFeedbackSeeder::class);
        // $this->call(DeliveryServiceSeeder::class);
        // $this->call(OfficeSeeder::class);
        // $this->call(FeatureSeeder::class);
        // $this->call(WeightPackageSeeder::class);
        // $this->call(ServiceAreaSeeder::class);
        // $this->call(ServiceAreaSettingSeeder::class);
        // $this->call(DistrictSeeder::class);
        // $this->call(UpazilaSeeder::class);
        // $this->call(AreaSeeder::class);
        // $this->call(MerchantSeeder::class);
        // $this->call(BranchSeeder::class);
        // $this->call(RiderSeeder::class);
        // $this->call(ParcelSeeder::class);
        // $this->call(ParcelLogSeeder::class);
        $this->call(RiderRunSeeder::class);
        $this->call(RiderRunDetailSeeder::class);
    }
}
