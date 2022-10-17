@extends('hito-admin::_layout')

@section('actions')
    @can('create', \App\Models\User::class)
        <a href="{{ route('admin.modules.index') }}"
           class="bg-blue-500 py-2 px-4 rounded text-white uppercase text-sm font-bold tracking-wide hover:bg-opacity-90">
            <i class="fas fa-file-invoice"></i> Installed modules
        </a>
    @endcan
@endsection

@section('content')
    @error('installation')
    <div class="rounded p-4 my-4 text-center bg-red-500 text-white font-bold">
        <i class="fas fa-exclamation-triangle"></i> <span>{{ $message }}</span>
    </div>
    @enderror

    @if(session('installed'))
        <div>
            <div class="rounded p-4 my-4 text-center bg-green-500 text-white font-bold">
                <i class="fas fa-exclamation-triangle"></i> <span>Module has been installed successfully</span>
            </div>
        </div>
    @endif

    @if(session('uninstalled'))
        <div>
            <div class="rounded p-4 my-4 text-center bg-green-500 text-white font-bold">
                <i class="fas fa-exclamation-triangle"></i> <span>Module has been uninstalled successfully</span>
            </div>
        </div>
    @endif

    @if(!empty($repositories))
        <div class="space-y-4 my-4">
            @foreach($repositories as $repository)
                <div class="space-y-4 bg-white rounded-lg shadow">
                    <div class="p-4 bg-gray-300">{{ $repository['repository']->getRepoName() }}</div>
                    <div class="p-4">
                        <table class="w-full">
                            <thead>
                            <tr>
                                <th class="w-1/6">Name</th>
                                <th class="w-1/6"></th>
                            </tr>
                            </thead>

                            @foreach($repository['packages'] as $module)
                                <tr class="rounded hover:bg-gray-200">
                                    <td class="text-left">
                                        <div class="p-2">
                                            <div>{{ $module['name'] }}</div>
                                            @if($module['installed'])
                                                <div class="text-sm">Installed version: <b>{{ $module['installed_version'] }}</b></div>

                                                @if($module['update_version'])
                                                    <div class="text-sm text-green-700 font-bold">Update available: <b>{{ $module['update_version'] }}</b></div>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <form
                                            action="{{ route('admin.modules.action') }}"
                                            method="post"
                                            class="px-2">
                                            @csrf
                                            <input type="hidden" name="module" value="{{ $module['name'] }}">
                                            <div class="text-right flex justify-end items-center space-x-2">
                                                @if($module['installed'] && auth()->user()->can('create', \App\Models\User::class))
                                                    <div>
                                                        <button type="submit" name="action" value="uninstall"
                                                                class="bg-red-500 hover:bg-red-400 text-white py-1 px-3 inline-block rounded uppercase text-sm font-bold">
                                                            Uninstall
                                                        </button>
                                                    </div>

                                                    @if(!is_null($module['update_version']))
                                                    <div>
                                                        <input type="hidden" name="module_version" value="{{ $module['update_version'] }}" />
                                                        <button type="submit" name="action" value="update"
                                                                class="bg-green-500 hover:bg-green-400 text-white py-1 px-3 inline-block rounded uppercase text-sm font-bold">
                                                            <i class="fas fa-arrow-up"></i> <span>Update</span>
                                                        </button>
                                                    </div>
                                                    @endif
                                                @endif

                                                @if(!$module['installed'] && auth()->user()->can('create', \App\Models\User::class))
                                                    <div>
                                                        @if ($module['is_local_package'])
                                                            <input name="module_version" hidden value="*">
                                                        @else
                                                        <select name="module_version" class="rounded py-1 px-2 bg-gray-100 border">
                                                            @foreach($module['string_versions'] as $version)
                                                                <option value="{{ $version }}">{{ $version }}</option>
                                                            @endforeach
                                                        </select>
                                                        @endif
                                                    </div>

                                                    <div>
                                                        <button type="submit" name="action" value="install"
                                                                class="bg-green-500 hover:bg-green-400 text-white py-1 px-3 inline-block rounded uppercase text-sm font-bold">
                                                            Install
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded p-4 my-4 text-center bg-yellow-500 text-white font-bold">
            <i class="fas fa-exclamation-triangle"></i> <span>There are no modules that can be installed.</span>
        </div>
    @endif
@endsection
