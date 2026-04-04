import type { Subtask } from "./Subtask";

export type Task = {
  id: number;
  title: string;
  description: string | null;
  userId: number | null;
  subtasks: Subtask[];
  isDone?: boolean;
  createdAt: string;
  updatedAt: string | null;
  deletedAt: string | null;
};
