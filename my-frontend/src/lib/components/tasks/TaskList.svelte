<script lang="ts">
  import TaskItem from "./TaskItem.svelte";
  import type { Task } from "$lib/_types/Task";
  import type { User } from "$lib/_types/User";

  let {
    tasks,
    users,
    assignUser,
    onToggle,
    deleteTask,
    addSubtask,
  }: {
    tasks: Task[];
    users: User[];
    assignUser: (taskId: number, userId: number | null) => Promise<void>;
    onToggle: (taskId: number, subtaskId: number) => Promise<void>;
    deleteTask: (taskId: number) => Promise<void>;
    addSubtask: (taskId: number, title: string) => Promise<void>;
  } = $props();
</script>

{#if tasks.length === 0}
  <div
    class="rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500"
  >
    Noch keine Tasks vorhanden.
  </div>
{:else}
  <div class="grid gap-5 md:grid-cols-2">
    {#each tasks as task}
      <TaskItem
        {task}
        {users}
        {assignUser}
        {onToggle}
        {deleteTask}
        {addSubtask}
      />
    {/each}
  </div>
{/if}
