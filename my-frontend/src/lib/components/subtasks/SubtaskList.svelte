<script lang="ts">
  import type { Subtask } from "$lib/_types/subtask";
  import SubtaskItem from "./SubtaskItem.svelte";

  export let subtasks: Subtask[];
  export let taskId: number;
  export let onToggle: (taskId: number, subtaskId: number) => Promise<void>;
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
