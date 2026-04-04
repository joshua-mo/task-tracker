<script lang="ts">
  import type { Task } from "$lib/_types/Task";

  import { onMount } from "svelte";
  import { user } from "$lib/stores/user";
  import { users } from "$lib/stores/user";
  import { token as tokenStore } from "$lib/stores/auth";
  import { goto } from "$app/navigation";

  import TaskList from "$lib/components/tasks/TaskList.svelte";
  import { post, retire, patch, get } from "$lib/api";
  import type { TaskForm } from "$lib/_types/TaskForm";
  import type { NewTaskPayload } from "$lib/_types/NewTaskPayload";

  // tasks
  let tasks = $state<Task[]>([]);
  let taskForm = $state<TaskForm>({
    title: "",
    description: "",
    userId: null,
  });

  // subtasks
  let showSubtaskInput = $state(false);
  let subtaskInput = $state("");
  let subtasks = $state<string[]>([]);

  // user and logout
  let openDropdown = $state(false);
  let initials = $derived(
    $user?.email ? $user.email.slice(0, 2).toUpperCase() : "?",
  );

  let filter = $state<"all" | "my">("all");

  // abhaengigkeiten -> usememo
  let filteredTasks = $derived(
    filter === "my" ? tasks.filter((t) => t.userId === $user?.id) : tasks,
  );

  function logout() {
    tokenStore.set(null);
    user.set(null);

    sessionStorage.removeItem("token");
    sessionStorage.removeItem("user");

    goto("/login");
  }

  // calls
  async function getTasks(): Promise<void> {
    try {
      const data: Task[] = await get("/tasks");

      tasks = data;
    } catch (err) {
      console.log(err);
    }
  }

  async function addTask(): Promise<void> {
    if (!taskForm.title.trim()) return;

    const newTask: NewTaskPayload = {
      title: taskForm.title.trim(),
      description: taskForm.description.trim(),
      userId: taskForm.userId,
      subtasks,
    };

    try {
      const data: Task = await post("/tasks", newTask);

      tasks = [data, ...tasks];
      taskForm.title = "";
      taskForm.description = "";
      subtasks = [];
      taskForm.userId = null;
    } catch (err) {
      console.log(err);
    }
  }

  async function deleteTask(id: number): Promise<void> {
    try {
      // console.log(id);
      await retire(`/tasks/${id}`);
      tasks = tasks.filter((task) => task.id != id);
    } catch (err) {
      console.log(err);
    }
  }

  async function toggleSubtask(
    taskId: number,
    subtaskId: number,
  ): Promise<void> {
    try {
      const data: Task = await patch(`/subtasks/${subtaskId}`);

      tasks = tasks.map((task) => (task.id === taskId ? data : task));
    } catch (err) {
      console.log(err);
    }
  }

  async function addSubtask(taskId: number, title: string): Promise<void> {
    try {
      const updatedTask: Task = await post(`/tasks/${taskId}/subtask`, {
        title,
      });

      tasks = tasks.map((task) => (task.id === taskId ? updatedTask : task));
    } catch (err) {
      console.log(err);
    }
  }

  async function assignUser(
    taskId: number,
    userId: number | null,
  ): Promise<void> {
    try {
      const data: Task = await patch(`/tasks/${taskId}/assign`, {
        userId: userId,
      });

      tasks = tasks.map((task) => (task.id === taskId ? data : task));
    } catch (err) {
      console.log(err);
    }
  }

  // init

  onMount(() => {
    getTasks();
  });
</script>

<div class="min-h-screen bg-slate-100">
  <div class="mx-auto max-w-4xl px-6 py-10">
    <!-- Avatar -->
    <div class="flex items-center justify-end mb-6">
      <div class="relative">
        <button
          class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-white font-semibold"
          onclick={() => (openDropdown = !openDropdown)}
        >
          {initials}
        </button>

        <!-- Dropdown -->
        {#if openDropdown}
          <div
            class="absolute right-0 mt-2 w-48 rounded-xl border bg-white shadow"
          >
            <div class="px-3 py-2 text-sm border-b">
              {$user?.email}
            </div>

            <button
              class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50"
              onclick={logout}
            >
              Logout
            </button>
          </div>
        {/if}
      </div>
    </div>
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
      </div>

      <div
        class="flex flex-col items-center rounded-2xl bg-white px-5 py-2 shadow-sm ring-1 ring-slate-200"
      >
        <p class="text-sm text-slate-500">Total</p>
        <p class="text-2xl font-semibold text-slate-900">
          {filteredTasks.length}
        </p>
      </div>
    </div>

    <div class="flex gap-2 mb-6">
      <button
        onclick={() => (filter = "all")}
        class={`px-3 py-1 rounded-xl text-sm ${
          filter === "all"
            ? "bg-slate-900 text-white"
            : "bg-slate-200 text-slate-700"
        }`}
      >
        All Tasks
      </button>
      <button
        onclick={() => (filter = "my")}
        class={`px-3 py-1 rounded-xl text-sm ${
          filter === "my"
            ? "bg-slate-900 text-white"
            : "bg-slate-200 text-slate-700"
        }`}
      >
        My Tasks
      </button>
    </div>

    <!-- neue task anlegen -->
    <div class="mb-8 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
      <div class="mb-5">
        <h2 class="text-xl font-semibold text-slate-900">Neue Task anlegen</h2>
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
            bind:value={taskForm.title}
            type="text"
            placeholder="Zum Beispiel: Dashboard bauen"
            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-500 outline-none transition focus:border-slate-400 focus:bg-white"
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
            bind:value={taskForm.description}
            rows="4"
            placeholder="Kurze Beschreibung der Aufgabe..."
            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-500 outline-none transition focus:border-slate-400 focus:bg-white"
          ></textarea>
        </div>

        <div>
          <label
            for="assign-user"
            class="mb-2 block text-sm font-medium text-slate-700"
          >
            Assign to
          </label>

          <select
            id="assign-user"
            bind:value={taskForm.userId}
            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3"
            class:text-slate-500={taskForm.userId}
            class:text-slate-300={!taskForm.userId}
          >
            <option value={null}>Unassigned</option>

            <!-- shows email but value is id  -->
            {#each $users as user}
              <option value={user.id}>{user.email}</option>
            {/each}
          </select>
        </div>

        <!-- Subtask -->

        {#if subtasks.length > 0}
          <div class="mb-3 space-y-2">
            <span class="text-xs text-slate-400">Subtasks</span>
            {#each subtasks as subtask}
              <div
                class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-500 bg-slate-50"
              >
                {subtask}
              </div>
            {/each}
          </div>
        {/if}

        <div class="mt-4">
          <button
            onclick={() => {
              showSubtaskInput = !showSubtaskInput;
            }}
            class="text-sm font-medium text-slate-600 hover:text-slate-900"
          >
            + Add Subtask
          </button>

          {#if showSubtaskInput}
            <div class="mt-2 flex gap-2">
              <input
                bind:value={subtaskInput}
                type="text"
                placeholder="Subtask Titel..."
                class="w-full rounded-xl border text-slate-500 border-slate-200 px-3 py-2 text-sm outline-none focus:border-slate-400"
              />

              <button
                onclick={() => {
                  if (!subtaskInput.trim()) return;
                  subtasks = [...subtasks, subtaskInput.trim()];
                  subtaskInput = "";
                  showSubtaskInput = false;
                }}
                class="rounded-xl bg-slate-900 px-3 py-2 text-sm text-white hover:bg-slate-800"
              >
                Add
              </button>
            </div>
          {/if}
        </div>

        <div class="flex justify-end">
          <button
            onclick={addTask}
            class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-medium text-white transition hover:translate-y-[-1px] hover:bg-slate-800"
          >
            Task hinzufügen
          </button>
        </div>
      </div>
    </div>

    <!-- tasks -->

    <TaskList
      tasks={filteredTasks}
      users={$users}
      {assignUser}
      onToggle={toggleSubtask}
      {deleteTask}
      {addSubtask}
    />
  </div>
</div>
