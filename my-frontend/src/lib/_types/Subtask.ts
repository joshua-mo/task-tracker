export type Subtask = {
  id: number;
  title: string;
  isDone: boolean;
  taskId: number;
  createdAt: string;
  updatedAt: string | null;
  deletedAt: string | null;
};
