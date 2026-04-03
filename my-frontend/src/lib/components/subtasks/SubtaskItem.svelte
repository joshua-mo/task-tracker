<script lang="ts">
  import type { Subtask } from "$lib/_types/subtask";

  export let subtask: Subtask;

  export let taskId: number;

  export let onToggle: (taskId: number, subtaskId: number) => Promise<void>;
</script>

<label
  class="flex cursor-pointer items-center justify-between rounded-xl border border-slate-200 px-4 py-3"
>
  <div class="flex items-center gap-3">
    <input
      type="checkbox"
      checked={subtask.isDone}
      on:change={() => onToggle(taskId, subtask.id)}
    />

    <span
      class:line-through={subtask.isDone}
      class:text-slate-400={subtask.isDone}
    >
      {subtask.title}
    </span>
  </div>
</label>
