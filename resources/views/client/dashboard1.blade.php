<x-app-layout>

 <div class="space-y-8">

  <x-dashboard.hero />
 
     <x-dashboard.usedtokens />

  <x-dashboard.quick-actions />

  {{--  <x-dashboard.activity-overview /> --}}

  <x-dashboard.recent-reports :recentReports="$recentReports" />

  <x-dashboard.recent-conversations />

  <x-dashboard.tax-profile-progress />

  {{--<x-dashboard.ai-recommendations />

  {{-- <x-dashboard.compliance-status />

  <x-dashboard.deadlines /> --}}

  {{-- <x-dashboard.documents-center /> --}}

  <x-dashboard.learning-center />

  {{-- <x-dashboard.subscription-usage /> --}}

  {{-- <x-dashboard.financial-health /> --}}

 </div>

</x-app-layout>