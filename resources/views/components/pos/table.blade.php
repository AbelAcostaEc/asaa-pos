@props(['headers' => [], 'paginate' => null])

<div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="shadow-sm overflow-hidden border border-gray-100 dark:border-gray-800 rounded-xl">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            @foreach($headers as $header)
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ $header }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-800">
                        {{ $slot }}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    @if($paginate)
        <div class="mt-4">
            {{ $paginate }}
        </div>
    @endif
</div>
