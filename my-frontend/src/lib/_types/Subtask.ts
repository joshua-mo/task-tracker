export type Subtask = {
  id: number;
  title: string;
  isDone: boolean;
  task_id: number;
  createdAt: string;
  updatedAt: string | null;
  deletedAt: string | null;
};
