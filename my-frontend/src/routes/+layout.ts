import { browser } from "$app/environment";
import "$lib/i18n/i18n";
import { locale, waitLocale } from "svelte-i18n";
import type { LayoutLoad } from "../../.svelte-kit/types/src/routes/$types";

import { redirect } from "@sveltejs/kit";

export const load: LayoutLoad = async ({ url }) => {
  if (browser) {
    locale.set(window.navigator.language);

    const token = sessionStorage.getItem("token");
    const isLoginRoute = url.pathname === "/login";

    if (!token && !isLoginRoute) {
      throw redirect(302, "/login");
    }

    if (token && isLoginRoute) {
      throw redirect(302, "/");
    }
  }
  await waitLocale();
  return {};
};
