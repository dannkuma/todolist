<!DOCTYPE html>
<html lang="ja">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Todo</title>
 
    @vite('resources/css/app.css')
</head>
 
<body class="flex flex-col min-h-[100vh]">
    <header class="bg-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="py-6">
                <p class="text-white text-xl">Todo Application</p>
            </div>
        </div>
    </header>
 
    <main class="grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="py-[100px]">
                <p class="text-2xl font-bold text-center">今日は何する？</p>
                <form action="/tasks" method="post" class="mt-5">
                  @csrf
 
                  <div class="flex flex-col items-center">
                    <label class="w-full max-w-3xl mx-auto">
                        <input
                            class="placeholder:italic placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-4 pl-4 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1 sm:text-sm"
                            placeholder="洗濯物をする..." type="text" name="task_name" />
                            @error('task_name')
                                <div class="mt-3">
                                    <p class="text-red-500">
                                        {{ $message }}
                                    </p>
                                </div>
                            @enderror
                    </label>
    
                    <button type="submit" class="mt-8 p-4 bg-slate-800 text-white w-full max-w-xs hover:bg-slate-900 transition-colors">
                        追加する
                    </button>
                  </div>
 
                </form>
                <div class="flex justify-center mt-8">
                    <form action="/tasks" method="get">
                    @csrf
                        <input type="hidden" name="condition" value="all">
                        <button type="submit" class="opacity-100 w-40 py-1 text-white text-sm flex justify-center items-center bg-emerald-700 rounded-xl hover:opacity-70 transition-opacity duration-300">一覧を表示</button>
                    </form>
                </div>
                <div class="flex justify-center mt-6">
                    <form action="/tasks" method="get">
                    @csrf    
                        <input type="hidden" name="condition" value="completion">
                        <button type="submit" class="@if($sort_condition !== null) @if($sort_condition === 'completion') bg-slate-900 text-white @else bg-gray-100 text-gray-500  @endif @endif 
                    bg-gray-100 w-32 py-1 px-8 box-border border border-solid border-gray-200 rounded-l-lg text-xs flex justify-center items-center">完了済み</button>
                    </form>
                    <form action="/tasks" method="get">
                    @csrf
                        <input type="hidden" name="condition" value="incomplete">    
                        <button type="submit" class="@if($sort_condition !== null) @if($sort_condition === 'incomplete') bg-slate-900 text-white @else bg-gray-100 text-gray-500  @endif @endif 
                    bg-gray-100 w-32 py-1 px-8 box-border border border-solid border-gray-200 rounded-r-lg text-xs flex justify-center items-center w-full">未完了</button>
                    </form>
                </div>

@if ($condition->isNotEmpty())
    <div class="max-w-7xl mx-auto mt-5">
          <div class="inline-block min-w-full py-2 align-middle">
              <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                  <table class="min-w-full divide-y divide-gray-300">
                      <thead class="bg-gray-50">
                          <tr>
                              <th scope="col"
                                  class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">
                                  タスク</th>
                              <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                  <span class="sr-only">Actions</span>
                              </th>
                          </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-200 bg-white">
                          @foreach ($condition as $item)
                              <tr>
                                  <td class="px-3 py-4 text-sm text-gray-500">
                                      <div>
                                          {{ $item->name }}
                                      </div>
                                  </td>
                                  <td class="p-0 text-right text-sm font-medium">
                                      <div class="flex justify-end">
                                          <div class="flex items-center">
                                              <form action="/tasks/{{ $item->id }}"
                                                  method="post"
                                                  class="inline-block text-gray-500 font-medium flex align-middle"
                                                  role="menuitem" tabindex="-1">
                                                  @csrf
                                                  @method('PUT')

                                                  <input type="hidden" name="status" value="{{$item->status}}">

                                                  <button type="submit"
                                                      class="@if($item->status === 1) bg-gray-300 w-20 text-white md:hover:bg-emerald-800 transition-colors h-10 
                                                      @else bg-emerald-700 w-20 text-white md:hover:bg-emerald-800 transition-colors h-10 @endif"
                                                       @if($item->status === 1) disabled @endif>完了</button>
                                              </form>
                                          </div>
                                          <div>
                                              <a href="/tasks/{{ $item->id }}/edit/"
                                                  class="inline-block text-center py-4 w-20 underline underline-offset-2 text-sky-600 md:hover:bg-sky-100 transition-colors">タスク名の<br>
                                                  編集</a>
                                          </div>
                                          <div class="flex items-center">
                                              <form onsubmit="return deleteTask();"
                                                  action="/tasks/{{ $item->id }}" method="post"
                                                  class="inline-block text-gray-500 font-medium"
                                                  role="menuitem" tabindex="-1">
                                                  @csrf
                                                  @method('DELETE')
                                                  <button type="submit"
                                                      class="w-20 md:hover:bg-slate-200 transition-colors h-10">削除</button>
                                              </form>
                                          </div>
                                      </div>
                                  </td>
                              </tr>
                            @endforeach
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  @else
    <div class="max-w-7xl mx-auto mt-5">
          <div class="inline-block min-w-full py-2 align-middle">
              <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                  <table class="min-w-full divide-y divide-gray-300">
                      <thead class="bg-gray-50">
                          <tr>
                              <th scope="col"
                                  class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">
                                  タスク</th>
                              <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                  <span class="sr-only">Actions</span>
                              </th>
                          </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-200 bg-white">
                            <tr>
                                <td class="px-3 py-4 text-sm text-gray-500">
                                    <div>
                                        Task Completed!!
                                    </div>
                                </td>
                            </tr>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  @endif
            </div>
        </div>
    </main>
    <footer class="bg-slate-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="py-4 text-center">
            <p class="text-white text-sm">Todo Application</p>
        </div>
    </div>
    </footer>
    <script>
        function deleteTask() {
            if (confirm('本当に削除しますか？')) {
                return true;
            } else {
                return false;
            }
        }
    </script>
</body>
 
</html>