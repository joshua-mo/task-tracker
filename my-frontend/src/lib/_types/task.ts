export type Task = {
  id: number;
  title: string;
  description: string;
  user_id?: number;
  createdAt: string;
  updatedAt: string | null;
  deletedAt: string | null;
};
