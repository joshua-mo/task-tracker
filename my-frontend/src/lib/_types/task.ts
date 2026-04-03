import type { Subtask } from "./subtask";

export type Task = {
  id: number;
  title: string;
  description: string;
  userId?: number;
  subtasks: Subtask[];
  isDone?: boolean | null;
  createdAt: string;
  updatedAt: string | null;
  deletedAt: string | null;
};
