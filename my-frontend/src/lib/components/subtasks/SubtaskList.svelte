<script lang="ts">
  import type { Subtask } from "$lib/_types/Subtask";
  import SubtaskItem from "./SubtaskItem.svelte";

  let {
    subtasks,
    taskId,
    onToggle,
  }: {
    subtasks: Subtask[];
    taskId: number;
    onToggle: (taskId: number, subtaskId: number) => Promise<void>;
  } = $props();
</script>

{#if subtasks.length > 0}
  {#each subtasks as subtask}
    <SubtaskItem {subtask} {taskId} {onToggle} />
  {/each}
{:else}
  <div
    class="rounded-xl border border-dashed border-slate-200 px-4 py-6 text-sm text-slate-400"
  >
    Keine Subtasks vorhanden
  </div>
{/if}
