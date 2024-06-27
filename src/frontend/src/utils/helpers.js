import { GridRowEditStopReasons, GridRowModes } from "@mui/x-data-grid";
import { useNavigate } from 'react-router-dom';

export default function useHelpers(
  rowModesModel,
  setRowModesModel,
  rows,
  setRows
) {

  const navigate = useNavigate();

  const infoClick = (id) => {
    const rowData = rows.find((row) => row.id === id);
    const domainId = rowData.id;
    const domainName = rowData.name;
    navigate(`/records/${id}`, { state: { domainId, domainName } });
  };

  const editClick = (id) => {
    setRowModesModel({ ...rowModesModel, [id]: { mode: GridRowModes.Edit } });
  };

  const deleteClick = (id) => {
    setRows(rows.filter((row) => row.id !== id));
  };

  const saveClick = (id) => {
    setRowModesModel({ ...rowModesModel, [id]: { mode: GridRowModes.View } });
  };

  const rowEditStop = (params, event) => {
    if (params.reason === GridRowEditStopReasons.rowFocusOut) {
      event.defaultMuiPrevented = true;
    }
  };

  const cancelClick = (id) => {
    setRowModesModel({
      ...rowModesModel,
      [id]: { mode: GridRowModes.View, ignoreModifications: true },
    });

    const editedRow = rows.find((row) => row.id === id);
    if (editedRow.isNew) {
      setRows(rows.filter((row) => row.id !== id));
    }
  };

  const processRowUpdate = (newRow) => {
    const updatedRow = { ...newRow, isNew: false };
    setRows(rows.map((row) => (row.id === newRow.id ? updatedRow : row)));
    return updatedRow;
  };

  const rowModesModelChange = (newRowModesModel) => {
    setRowModesModel(newRowModesModel);
  };

  return {
    infoClick,
    editClick,
    deleteClick,
    rowEditStop,
    saveClick,
    cancelClick,
    processRowUpdate,
    rowModesModelChange,
  };
}
