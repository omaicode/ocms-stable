<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Class CreateLanguagesTable.
 */
class CreateLanguagesTable extends Migration
{
    public function createDefaultLanguages()
    {
        $languages = [
            ['code' => 'vi', 'name' => 'Tiếng Việt', 'icon' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjUiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNSAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMTIuODc1IiBjeT0iMTIiIHI9IjEyIiBmaWxsPSIjRDgwMDI3Ii8+CjxwYXRoIGQ9Ik0xMi44MTI1IDVMMTQuNDUwMyAxMC4wNDAzSDE5Ljc1TDE1LjQ2MjMgMTMuMTU1NEwxNy4xMDAyIDE4LjE5NThMMTIuODEyNSAxNS4wODA3TDguNTI0ODQgMTguMTk1OEwxMC4xNjI3IDEzLjE1NTRMNS44NzUgMTAuMDQwM0gxMS4xNzQ3TDEyLjgxMjUgNVoiIGZpbGw9IiNGRkRBNDQiLz4KPC9zdmc+Cg=='],
            ['code' => 'en', 'name' => 'English', 'icon' => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGcgY2xpcC1wYXRoPSJ1cmwoI2NsaXAwXzY3Ml8zMjYwKSI+CjxwYXRoIGQ9Ik0yNCAwLjAwMDE4MzEwNUgwVjI0SDI0VjAuMDAwMTgzMTA1WiIgZmlsbD0iI0YwRjBGMCIvPgo8cGF0aCBkPSJNMTMuNSAwSDEwLjVWOS43NDk3NUgwVjE0LjI0OTdIMTAuNVYyMy45OTk0SDEzLjVWMTQuMjQ5N0gyNFY5Ljc0OTc1SDEzLjVWMFoiIGZpbGw9IiNEODAwMjciLz4KPHBhdGggZD0iTTE4LjQ1ODYgMTYuMTczNUwyMy45OTk5IDIwLjc5MTNWMTYuMTczNUgxOC40NTg2WiIgZmlsbD0iIzAwNTJCNCIvPgo8cGF0aCBkPSJNMTQuNjA4NSAxNi4xNzM1TDIzLjk5OTggMjMuOTk5NFYyMS43ODY1TDE3LjI2NDIgMTYuMTczNUgxNC42MDg1WiIgZmlsbD0iIzAwNTJCNCIvPgo8cGF0aCBkPSJNMjEuNDk4MiAyMy45OTk0TDE0LjYwODUgMTguMjU3NVYyMy45OTk0SDIxLjQ5ODJaIiBmaWxsPSIjMDA1MkI0Ii8+CjxwYXRoIGQ9Ik0xNC42MDg1IDE2LjE3MzVMMjMuOTk5OCAyMy45OTk0VjIxLjc4NjVMMTcuMjY0MiAxNi4xNzM1SDE0LjYwODVaIiBmaWxsPSIjRjBGMEYwIi8+CjxwYXRoIGQ9Ik0xNC42MDg1IDE2LjE3MzVMMjMuOTk5OCAyMy45OTk0VjIxLjc4NjVMMTcuMjY0MiAxNi4xNzM1SDE0LjYwODVaIiBmaWxsPSIjRDgwMDI3Ii8+CjxwYXRoIGQ9Ik00LjIzNDczIDE2LjE3MzRMMCAxOS43MDIzVjE2LjE3MzRINC4yMzQ3M1oiIGZpbGw9IiMwMDUyQjQiLz4KPHBhdGggZD0iTTkuMzkxMTYgMTcuMTY4NVYyMy45OTkzSDEuMTk0NzNMOS4zOTExNiAxNy4xNjg1WiIgZmlsbD0iIzAwNTJCNCIvPgo8cGF0aCBkPSJNNi43MzU2MSAxNi4xNzM1TDAgMjEuNzg2NVYyMy45OTk0TDkuMzkxMzEgMTYuMTczNUg2LjczNTYxWiIgZmlsbD0iI0Q4MDAyNyIvPgo8cGF0aCBkPSJNNS41NDEzMyA3LjgyNTkxTDAgMy4yMDgxNlY3LjgyNTkxSDUuNTQxMzNaIiBmaWxsPSIjMDA1MkI0Ii8+CjxwYXRoIGQ9Ik05LjM5MTMxIDcuODI1OUwwIDBWMi4yMTI5N0w2LjczNTYxIDcuODI1OUg5LjM5MTMxWiIgZmlsbD0iIzAwNTJCNCIvPgo8cGF0aCBkPSJNMi41MDEzNyAwTDkuMzkxMTYgNS43NDE4OFYwSDIuNTAxMzdaIiBmaWxsPSIjMDA1MkI0Ii8+CjxwYXRoIGQ9Ik05LjM5MTMxIDcuODI1OUwwIDBWMi4yMTI5N0w2LjczNTYxIDcuODI1OUg5LjM5MTMxWiIgZmlsbD0iI0YwRjBGMCIvPgo8cGF0aCBkPSJNOS4zOTEzMSA3LjgyNTlMMCAwVjIuMjEyOTdMNi43MzU2MSA3LjgyNTlIOS4zOTEzMVoiIGZpbGw9IiNEODAwMjciLz4KPHBhdGggZD0iTTE5Ljc2NTEgNy44MjYwNkwyMy45OTk5IDQuMjk3MTVWNy44MjYwNkgxOS43NjUxWiIgZmlsbD0iIzAwNTJCNCIvPgo8cGF0aCBkPSJNMTQuNjA4NSA2LjgzMDg0VjYuMTAzNTJlLTA1SDIyLjgwNDlMMTQuNjA4NSA2LjgzMDg0WiIgZmlsbD0iIzAwNTJCNCIvPgo8cGF0aCBkPSJNMTcuMjY0MiA3LjgyNTlMMjMuOTk5OCAyLjIxMjk3VjBMMTQuNjA4NSA3LjgyNTlIMTcuMjY0MloiIGZpbGw9IiNEODAwMjciLz4KPC9nPgo8ZGVmcz4KPGNsaXBQYXRoIGlkPSJjbGlwMF82NzJfMzI2MCI+CjxyZWN0IHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgcng9IjEyIiBmaWxsPSJ3aGl0ZSIvPgo8L2NsaXBQYXRoPgo8L2RlZnM+Cjwvc3ZnPgo='],
            // ['code' => 'de', 'name' => 'Germany', 'icon' => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1MTIiIGhlaWdodD0iNTEyIiB2aWV3Qm94PSIwIDAgNTEyIDUxMiI+PG1hc2sgaWQ9ImEiPjxjaXJjbGUgY3g9IjI1NiIgY3k9IjI1NiIgcj0iMjU2IiBmaWxsPSIjZmZmIi8+PC9tYXNrPjxnIG1hc2s9InVybCgjYSkiPjxwYXRoIGZpbGw9IiNmZmRhNDQiIGQ9Im0wIDM0NSAyNTYuNy0yNS41TDUxMiAzNDV2MTY3SDB6Ii8+PHBhdGggZmlsbD0iI2Q4MDAyNyIgZD0ibTAgMTY3IDI1NS0yMyAyNTcgMjN2MTc4SDB6Ii8+PHBhdGggZmlsbD0iIzMzMyIgZD0iTTAgMGg1MTJ2MTY3SDB6Ii8+PC9nPjwvc3ZnPg=='],
            ['code' => 'ko', 'name' => 'Korea', 'icon' => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1MTIiIGhlaWdodD0iNTEyIiB2aWV3Qm94PSIwIDAgNTEyIDUxMiI+PG1hc2sgaWQ9ImEiPjxjaXJjbGUgY3g9IjI1NiIgY3k9IjI1NiIgcj0iMjU2IiBmaWxsPSIjZmZmIi8+PC9tYXNrPjxnIG1hc2s9InVybCgjYSkiPjxwYXRoIGZpbGw9IiNlZWUiIGQ9Ik0wIDBoNTEydjUxMkgwWiIvPjxwYXRoIGZpbGw9IiMzMzMiIGQ9Im0zNTAgMzM1IDI0LTI0IDE2IDE2LTI0IDIzem0tMzkgMzkgMjQtMjQgMTUgMTYtMjMgMjR6bTg3IDggMjMtMjQgMTYgMTYtMjQgMjR6bS00MCAzOSAyNC0yMyAxNiAxNS0yNCAyNFptMTYtNjMgMjQtMjMgMTUgMTUtMjMgMjR6bS0zOSA0MCAyMy0yNCAxNiAxNi0yNCAyM3ptNjMtMjIxLTYzLTYzIDE1LTE1IDY0IDYzem0tNjMtMTUtMjQtMjQgMTYtMTYgMjMgMjR6bTM5IDM5LTI0LTI0IDE2LTE1IDI0IDIzem04LTg3LTI0LTIzIDE2LTE2IDI0IDI0Wm0zOSA0MC0yMy0yNCAxNS0xNiAyNCAyNFpNOTEgMzU4bDYzIDYzLTE2IDE2LTYzLTYzem02MyAxNiAyMyAyNC0xNSAxNS0yNC0yM3ptLTQwLTM5IDI0IDIzLTE2IDE2LTIzLTI0em0yNC0yNCA2MyA2My0xNiAxNi02My02M3ptMTYtMjIwLTYzIDYzLTE2LTE2IDYzLTYzem0yMyAyMy02MyA2My0xNS0xNiA2My02M3ptMjQgMjQtNjMgNjMtMTYtMTYgNjMtNjN6Ii8+PHBhdGggZmlsbD0iI2Q4MDAyNyIgZD0iTTMxOSAzMTkgMTkzIDE5M2E4OSA4OSAwIDEgMSAxMjYgMTI2eiIvPjxwYXRoIGZpbGw9IiMwMDUyYjQiIGQ9Ik0zMTkgMzE5YTg5IDg5IDAgMSAxLTEyNi0xMjZ6Ii8+PGNpcmNsZSBjeD0iMjI0LjUiIGN5PSIyMjQuNSIgcj0iNDQuNSIgZmlsbD0iI2Q4MDAyNyIvPjxjaXJjbGUgY3g9IjI4Ny41IiBjeT0iMjg3LjUiIHI9IjQ0LjUiIGZpbGw9IiMwMDUyYjQiLz48L2c+PC9zdmc+'],
            // ['code' => 'jp', 'name' => 'Japan', 'icon' => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1MTIiIGhlaWdodD0iNTEyIiB2aWV3Qm94PSIwIDAgNTEyIDUxMiI+PG1hc2sgaWQ9ImEiPjxjaXJjbGUgY3g9IjI1NiIgY3k9IjI1NiIgcj0iMjU2IiBmaWxsPSIjZmZmIi8+PC9tYXNrPjxnIG1hc2s9InVybCgjYSkiPjxwYXRoIGZpbGw9IiNlZWUiIGQ9Ik0wIDBoNTEydjUxMkgweiIvPjxjaXJjbGUgY3g9IjI1NiIgY3k9IjI1NiIgcj0iMTExLjMiIGZpbGw9IiNkODAwMjciLz48L2c+PC9zdmc+']
        ];

        $languages = array_map(function($lang) {
            return array_merge($lang, [
                'created_at' => now(),
                'active' => true
            ]);
        }, $languages);

        DB::table('languages')->insert($languages);
    }

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('languages', function(Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->longText('icon')->nullable();
            $table->string('name');
            $table->boolean('active')->default(false);
            $table->timestamps();
		});

        $this->createDefaultLanguages();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('languages');
	}
}
