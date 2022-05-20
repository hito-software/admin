@extends('hito-admin::_layout')

@section('actions')
    @can('create', \App\Models\User::class)
        <a href="{{ route('admin.modules.available') }}"
           class="bg-blue-500 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
            <i class="fas fa-file-invoice"></i> Available modules
        </a>
    @endcan
@endsection

@section('content')
    @if(!empty($modules))
        <div class="space-y-4 my-4 bg-white p-4 rounded-lg shadow">
            <table class="w-full">
                <thead>
                <tr>
                    <th class="w-1/6">ID</th>
                    <th class="w-1/6">Name</th>
                    <th class="w-1/6 text-center">Status</th>
                    <th class="w-1/6"></th>
                </tr>
                </thead>

                @foreach($modules as $module)
                    <tr>
                        <td class="text-center">{{ $module->getId() }}</td>
                        <td class="text-center">{{ $module->getName() }}</td>
                        <td class="text-center">{{ $module->isEnabled() ? 'Enabled' : 'Disabled' }}</td>
                        <td>
                            <form action="{{ route('admin.modules.toggle') }}" method="post">
                                @csrf
                                <input type="hidden" name="module" value="{{ $module->getId() }}">
                                <div class="text-right">
                                    @if(auth()->user()->can('update', $module))
                                        @if(!$module->isEnabled())
                                            <button type="submit"
                                                    class="bg-green-500 hover:bg-green-400 text-white py-1 px-3 inline-block rounded uppercase text-sm font-bold">
                                                Enable
                                            </button>
                                        @else
                                            <button type="submit"
                                                    class="bg-red-500 hover:bg-red-400 text-white py-1 px-3 inline-block rounded uppercase text-sm font-bold">
                                                Disable
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    @else
        <div class="rounded p-4 my-4 text-center bg-yellow-500 text-white font-bold">
            <i class="fas fa-exclamation-triangle"></i> <span>There are no modules installed.</span>
        </div>
    @endif
@endsection
