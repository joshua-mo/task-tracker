<script lang="ts">
  import type { Subtask } from "$lib/_types/Subtask";

  let {
    subtask,
    taskId,
    onToggle,
  }: {
    subtask: Subtask;
    taskId: number;
    onToggle: (taskId: number, subtaskId: number) => Promise<void>;
  } = $props();
</script>

<label
  class="flex cursor-pointer items-center justify-between rounded-xl border border-slate-200 px-4 py-3"
>
  <div class="flex items-center gap-3">
    <input
      type="checkbox"
      checked={subtask.isDone}
      onchange={() => onToggle(taskId, subtask.id)}
    />

    <span
      class:line-through={subtask.isDone}
      class:text-slate-400={subtask.isDone}
    >
      {subtask.title}
    </span>
  </div>
</label>
