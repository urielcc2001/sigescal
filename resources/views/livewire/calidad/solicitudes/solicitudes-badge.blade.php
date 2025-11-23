<div class="flex gap-1 items-center">

    @if($scope === 'solicitudes' && $countSolicitudes > 0)
        <span class="rounded-full bg-blue-600 text-white px-2 py-0.5 text-xs font-semibold">
            {{ $countSolicitudes }}
        </span>
    @endif

    @if($scope === 'complaints' && $countComplaints > 0)
        <span class="rounded-full bg-purple-600 text-white px-2 py-0.5 text-xs font-semibold">
            {{ $countComplaints }}
        </span>
    @endif

</div>
