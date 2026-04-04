export type NewTaskPayload = {
  title: string;
  description: string;
  userId: number | null;
  subtasks: string[];
};
