<script lang="ts">
  import type { Task } from "$lib/_types/task";
  import { onMount } from "svelte";

  let tasks: Task[] = [];

  const API_URL: string = "http://127.0.0.1:8080";

  async function getTasks(): Promise<void> {
    try {
      const res = await fetch(`${API_URL}/api/tasks`);
      const data: Task[] = await res.json();

      tasks = data;
      console.log(tasks);
    } catch (err) {
      console.log(err);
    }
  }

  onMount(() => {
    getTasks();
  });

  let title = "";
  let description = "";

  async function addTask(): Promise<void> {
    if (!title.trim()) return;

    const newTask = {
      title: title.trim(),
      description: description.trim(),
    };

    try {
      const res = await fetch(`${API_URL}/api/tasks`, {
        method: "POST",
        body: JSON.stringify(newTask),
        headers: {
          "Content-Type": "application/json",
        },
      });

      const data: Task = await res.json();

      tasks = [data, ...tasks];
      title = "";
      description = "";
    } catch (err) {
      console.log(err);
    }
  }

  async function deleteTask(id: number): Promise<void> {
    try {
      // console.log(id);
      await fetch(`${API_URL}/api/tasks/${id}`, {
        method: "DELETE",
      });
      tasks = tasks.filter((task) => task.id != id);
    } catch (err) {
      console.log(err);
    }
  }

  async function deleteAllTasks(): Promise<void> {
    try {
      // console.log(id);
      await fetch(`${API_URL}/api/tasks/all`, {
        method: "DELETE",
      });
      tasks = [];
    } catch (err) {
      console.log(err);
    }
  }
</script>

<div class="min-h-screen bg-slate-100">
  <div class="mx-auto max-w-4xl px-6 py-10">
    <div class="mb-8 flex items-end justify-between gap-4">
      <div>
        <p
          class="mb-2 text-sm font-medium uppercase tracking-[0.2em] text-slate-500"
        >
          Task Tracker
        </p>
        <h1 class="text-4xl font-bold tracking-tight text-slate-900">
          Overview
        </h1>
        <p class="mt-3 max-w-2xl text-slate-600">See your tasks below.</p>
      </div>

      <button
        on:click={deleteAllTasks}
        class="bg-red-50/50 px-2 py-1 rounded-xl ml-auto text-gray-500 text-sm hover:scale-75 hover:cursor-pointer hover:bg-red-950 hover:text-gray-100 transition-all ease-out"
        >Delete All Tasks</button
      >

      <div
        class="flex flex-col items-center rounded-2xl bg-white px-5 py-2 shadow-sm ring-1 ring-slate-200"
      >
        <p class="text-sm text-slate-500">Total</p>
        <p class="text-2xl font-semibold text-slate-900">{tasks.length}</p>
      </div>
    </div>

    <!-- neue task anlegen -->
    <div class="mb-8 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
      <div class="mb-5">
        <h2 class="text-xl font-semibold text-slate-900">Neue Task anlegen</h2>
        <p class="mt-1 text-sm text-slate-500">
          Noch lokal mit Dummy Data, später dann mit deinem Backend.
        </p>
      </div>

      <div class="grid gap-4">
        <div>
          <label
            for="title"
            class="mb-2 block text-sm font-medium text-slate-700"
          >
            Titel
          </label>
          <input
            id="title"
            bind:value={title}
            type="text"
            placeholder="Zum Beispiel: Dashboard bauen"
            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white"
          />
        </div>

        <div>
          <label
            for="description"
            class="mb-2 block text-sm font-medium text-slate-700"
          >
            Beschreibung
          </label>
          <textarea
            id="description"
            bind:value={description}
            rows="4"
            placeholder="Kurze Beschreibung der Aufgabe..."
            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white"
          ></textarea>
        </div>

        <div class="flex justify-end">
          <button
            on:click={addTask}
            class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-medium text-white transition hover:translate-y-[-1px] hover:bg-slate-800"
          >
            Task hinzufügen
          </button>
        </div>
      </div>
    </div>

    <!-- tasks -->

    {#if tasks.length === 0}
      <div
        class="rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500"
      >
        Noch keine Tasks vorhanden.
      </div>
    {:else}
      <div class="grid gap-5 md:grid-cols-2">
        {#each tasks as task}
          <div
            class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:shadow-md"
          >
            <div class="mb-4 flex items-start justify-between gap-3">
              <div>
                <p
                  class="mb-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400"
                >
                  Task #{task.id}
                </p>
                <h3 class="text-xl font-semibold text-slate-900">
                  {task.title}
                </h3>
              </div>

              <div
                class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600"
              >
                Open
              </div>
            </div>

            <p class="text-sm leading-6 text-slate-600">
              {task.description || "Keine Beschreibung vorhanden."}
            </p>

            <div>
              <span class="text-sm text-slate-500">Assigned:</span>
              <span class="text-sm text-slate-500"
                >@ #{task.user_id ?? "3276"} Joshua</span
              >
            </div>

            <div
              class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4"
            >
              <span class="text-xs text-slate-400">Sub Tasks</span>
              <button
                on:click={() => {
                  deleteTask(task.id);
                }}
                class="bg-red-50/50 px-2 py-1 rounded-xl text-gray-500 text-sm hover:scale-75 hover:cursor-pointer hover:bg-red-950 hover:text-gray-100 transition-all ease-out"
                >Delete</button
              >
              <!-- <button
                class="text-sm font-medium text-slate-700 hover:text-slate-900"
              >
                Details
              </button> -->
            </div>
          </div>
        {/each}
      </div>
    {/if}
  </div>
</div>
