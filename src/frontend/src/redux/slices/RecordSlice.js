import { createSlice } from "@reduxjs/toolkit";
import { mockDataRecord } from "../../data/mockData";

const initialState = {
  records: mockDataRecord,
  isFetch: false,
  isErr: false,
};

export const RecordSlice = createSlice({
  name: "records",
  initialState,
  reducers: {
    deleteRecordsSuccess(state, action) {
      state.isFetch = false;
      state.isErr = false;
      const arrIds = action.payload;
      state.records = state.records.filter(
        (record) => !arrIds.includes(record.id)
      );
    },
  },
});

export const { deleteRecordsSuccess } = RecordSlice.actions;
export default RecordSlice.reducer;
