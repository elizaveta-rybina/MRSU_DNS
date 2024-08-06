import { createSlice } from '@reduxjs/toolkit';
import { fetchDomainsThunk } from '../../api/domains';

const initialState = {
  domains: [],
  isFetch: false,
  isErr: false,
};

const domainSlice = createSlice({
  name: 'domains',
  initialState,
  reducers: {
    deleteDomainsSuccess(state, action) {
      state.isFetch = false;
      state.isErr = false;
      const arrIds = action.payload;
      state.domains = state.domains.filter(
        (domain) => !arrIds.includes(domain.id)
      );
    },
  },
  extraReducers: (builder) => {
    builder
      .addCase(fetchDomainsThunk.pending, (state) => {
        state.isFetch = true;
        state.isErr = false;
      })
      .addCase(fetchDomainsThunk.fulfilled, (state, action) => {
        state.isFetch = false;
        state.domains = action.payload; // Обновление доменов
      })
      .addCase(fetchDomainsThunk.rejected, (state) => {
        state.isFetch = false;
        state.isErr = true;
      });
  },
});

export const { deleteDomainsSuccess } = domainSlice.actions;
export default domainSlice.reducer;
