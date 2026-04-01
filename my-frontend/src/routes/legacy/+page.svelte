<script lang="ts">
import { onMount } from "svelte";
import toast, { Toaster } from 'svelte-french-toast';



	let email: string = 'test@test.com';
	let password: string = '123456';
	let token:string = '';

    type User = {
        id: number, email: string
    }

	let user: User | null = null;
	let error:string = '';
	let loading:boolean = false;

	const API_URL:string = 'http://127.0.0.1:8080';

    onMount(()=>{
        const savedToken = localStorage.getItem("token")
        if(savedToken){
            token = savedToken 
                toast('Found User!', {
                icon: '👤',
        });
        
        }else{
            toast.error('No user logged in!');
        }
   
    })

	async function login() {
		error = '';
		user = null;
		loading = true;

		try {
			const response = await fetch(`${API_URL}/api/auth/authenticate`, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify({ email, password })
			});

			const data = await response.json();

			if (!response.ok) {
				throw new Error(data?.message || 'Login failed');
			}

			token = data.token;
			localStorage.setItem('token', token);

			await loadMe();
		} catch (e) {
			error = e instanceof Error ? e.message : 'Something went wrong';
		} finally {
			loading = false;
		}
	}

	async function loadMe() {
		error = '';

		try {
			const savedToken = token || localStorage.getItem('token');

			if (!savedToken) {
				throw new Error('No token found');
                   
			}

			const response = await fetch(`${API_URL}/api/auth/me`, {
				headers: {
					Authorization: `Bearer ${savedToken}`
				}
			});

			const data = await response.json();

			if (!response.ok) {
				throw new Error(data?.message || 'Could not load user');
			}

			user = data;
			token = savedToken;
              toast.success('Showing User details!');
		} catch (e) {
              toast.error('Error loading user info!');
			error = e instanceof Error ? e.message : 'Something went wrong';
		}
	}

	function logout() {
		token = '';
		user = null;
		error = '';
		localStorage.removeItem('token');
         toast.error('User logged out!');
	}
</script>
<Toaster />

<div class="mx-auto mt-16 max-w-xl space-y-6 rounded-xl border border-gray-200 p-8 shadow dark:border-gray-700">
	<h1 class="text-3xl font-bold dark:text-white">Lenkrad Auth Test</h1>

	<div class="space-y-4">
		<div>
			<label for="email" class="mb-1 block text-sm font-medium dark:text-white">Email</label>
			<input
				id="email"
				bind:value={email}
				type="email"
				class="w-full rounded-lg border border-gray-300 p-3 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
			/>
		</div>

		<div>
			<label for="password" class="mb-1 block text-sm font-medium dark:text-white">Password</label>
			<input
				id="password"
				bind:value={password}
				type="password"
				class="w-full rounded-lg border border-gray-300 p-3 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
			/>
		</div>

		<div class="flex gap-3">
			<button
				on:click={login}
				class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
				disabled={loading}
			>
				{loading ? 'Loading...' : 'Login'}
			</button>

			<button
				on:click={loadMe}
				class="rounded-lg bg-gray-700 px-4 py-2 text-white hover:bg-gray-800"
			>
				Load me
			</button>

			<button
				on:click={logout}
				class="rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700"
			>
				Logout
			</button>
		</div>
	</div>

	{#if error}
		<div class="rounded-lg bg-red-100 p-3 text-red-700">
			{error}
		</div>
	{/if}

	{#if token}
		<div class="rounded-lg bg-gray-100 p-3 text-sm break-all dark:bg-gray-800 dark:text-white">
			<strong>Token:</strong><br />
			{token}
		</div>
	{/if}

	{#if user}
		<div class="rounded-lg bg-green-100 p-3 text-green-800">
			<div><strong>Authenticated user:</strong></div>
			<div>ID: {user.id}</div>
			<div>Email: {user.email}</div>
		</div>
	{/if}
</div>