<script lang="ts">
  import SubtaskList from "../subtasks/SubtaskList.svelte";
  import type { Task } from "$lib/_types/Task";
  import type { User } from "$lib/_types/User";

  export let task: Task;

  export let users: User[];

  export let assignUser: (
    taskId: number,
    userId: number | null,
  ) => Promise<void>;

  export let onToggle: (taskId: number, subtaskId: number) => Promise<void>;

  export let deleteTask: (taskId: number) => Promise<void>;

  export let addSubtask: (taskId: number, title: string) => Promise<void>;

  let showSubtaskInput = false;
  let subtaskTitle = "";
</script>

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
      class={`rounded-full px-3 py-1 text-xs font-medium ${
        task.isDone
          ? "bg-green-100 text-green-700"
          : "bg-slate-100 text-slate-600"
      }`}
    >
      {task.isDone ? "done" : "open"}
    </div>
  </div>

  <p class="text-sm leading-6 text-slate-600">
    {task.description || "Keine Beschreibung vorhanden."}
  </p>

  <div class="flex items-center justify-start gap-2">
    <span class="text-sm text-slate-500">Assigned:</span>

    <!-- Dropdown  -->
    <select
      class="bg-slate-50 text-xs rounded-lg border border-slate-200 px-2 py-1"
      on:change={(e) => {
        const value = (e.currentTarget as HTMLSelectElement).value;
        assignUser(task.id, value ? Number(value) : null);
      }}
    >
      <option value="">Unassigned</option>

      {#each users as u}
        <option value={u.id} selected={u.id === task.userId}>
          {u.email}
        </option>
      {/each}
    </select>

    <button
      on:click={() => deleteTask(task.id)}
      class="bg-red-50/50 px-2 py-1 rounded-xl text-gray-500 text-sm ml-auto hover:scale-75 hover:cursor-pointer hover:bg-red-950 hover:text-gray-100 transition-all ease-out"
    >
      Delete Task
    </button>
  </div>

  <div
    class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4"
  >
    <span class="text-xs text-slate-400">Subtasks</span>

    <button
      class="text-sm font-medium text-slate-600 hover:text-slate-900"
      on:click={() => (showSubtaskInput = !showSubtaskInput)}
    >
      + Add Subtask
    </button>
  </div>

  {#if showSubtaskInput}
    <div class="mt-3 flex gap-2">
      <input
        bind:value={subtaskTitle}
        type="text"
        placeholder="Subtask Titel..."
        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none focus:border-slate-400"
      />

      <button
        on:click={() => {
          if (!subtaskTitle.trim()) return;
          addSubtask(task.id, subtaskTitle.trim());
          subtaskTitle = "";
          showSubtaskInput = false;
        }}
        class="rounded-xl bg-slate-900 px-3 py-2 text-sm text-white hover:bg-slate-800"
      >
        Add
      </button>
    </div>
  {/if}

  <div class="mt-4 space-y-2">
    <SubtaskList subtasks={task.subtasks} taskId={task.id} {onToggle} />
  </div>
</div>
