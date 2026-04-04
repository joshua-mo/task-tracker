<script lang="ts">
  import { goto } from "$app/navigation";
  import { get, post } from "$lib/api";
  import { token as tokenStore } from "$lib/stores/auth";
  import { user as userStore } from "$lib/stores/user";
  import type { User } from "$lib/_types/User";

  let email = $state("test@test.com");
  let password = $state("123456");
  let token = $state("");

  let user = $state<User | null>(null);
  let error = $state("");
  let loading = $state(false);

  async function login() {
    error = "";
    user = null;
    loading = true;

    try {
      const response = await post("/auth/authenticate", { email, password });

      token = response.token;
      tokenStore.set(token);
      sessionStorage.setItem("token", token);

      const me = await get("/auth/me");

      user = me;
      userStore.set(me);
      sessionStorage.setItem("user", JSON.stringify(me));

      await goto("/");
    } catch (e) {
      error = e instanceof Error ? e.message : "Something went wrong";
    } finally {
      loading = false;
    }
  }
</script>

<div
  class="mx-auto mt-16 max-w-xl space-y-6 rounded-xl border border-gray-200 p-8 shadow dark:border-gray-700"
>
  <h1 class="text-3xl font-bold dark:text-white">Lenkrad Auth Test</h1>

  <div class="space-y-4">
    <div>
      <label for="email" class="mb-1 block text-sm font-medium dark:text-white"
        >Email</label
      >
      <input
        id="email"
        bind:value={email}
        type="email"
        class="w-full rounded-lg border border-gray-300 p-3 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
      />
    </div>

    <div>
      <label
        for="password"
        class="mb-1 block text-sm font-medium dark:text-white">Password</label
      >
      <input
        id="password"
        bind:value={password}
        type="password"
        class="w-full rounded-lg border border-gray-300 p-3 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
      />
    </div>

    <div class="flex gap-3">
      <button
        onclick={login}
        class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
        disabled={loading}
      >
        {loading ? "Loading..." : "Login"}
      </button>
    </div>
  </div>

  {#if error}
    <div class="rounded-lg bg-red-100 p-3 text-red-700">
      {error}
    </div>
  {/if}
</div>
