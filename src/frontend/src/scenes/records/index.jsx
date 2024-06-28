
import DeleteOutlinedIcon from "@mui/icons-material/DeleteOutlined";
import EditOutlinedIcon from "@mui/icons-material/EditOutlined";
import { Box, useTheme } from "@mui/material";
import { DataGrid, GridActionsCellItem, GridRowModes } from "@mui/x-data-grid";
import { createSelector } from "@reduxjs/toolkit";
import * as React from "react";
import { useCallback, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { useLocation, useParams } from "react-router-dom";
import SaveCancelButtons from "../../components/Buttons/SaveCancelButtons";
import Header from "../../components/Header/Header";
import HeaderButtons from "../../components/Header/HeaderButtons";
import { deleteRecordsSuccess } from "../../redux/slices/RecordSlice";
import { tokens } from "../../theme";
import useHelpers from "../../utils/helpers";


// EditToolbar component
const EditToolbar = React.memo(function EditToolbar({
  setRows,
  setRowModesModel,
  rows,
  arrIds,
}) {
  const dispatch = useDispatch();
  const location = useLocation();
  const { domainName } = location.state || {};

  console.log("рендеринг компонента EditToolbar");

  const handleClick = useCallback(() => {
    const maxId = rows.reduce((max, row) => (row.id > max ? row.id : max), 0);
    const id = maxId + 1;
    setRows((oldRows) => [
      ...oldRows,
      {
        id,
        type: "",
        value: "",
        priority: 0,
        ttl: 0,
        domainId: 0, // исправить на нормальное
        isNew: true,
      },
    ]);
    setRowModesModel((oldModel) => ({
      ...oldModel,
      [id]: { mode: GridRowModes.Edit, fieldToFocus: "type" },
    }));
  }, [rows, setRows, setRowModesModel]);

  const handleDeleteAll = useCallback(() => {
    dispatch(deleteRecordsSuccess(arrIds));
  }, [dispatch, arrIds]);

  return (
    <Box>
      <Header title="DNS-записи" subtitle={domainName} />
      <HeaderButtons
        addInscription="Добавить запись"
        deleteInscription="Удалить запись"
        handleClick={handleClick}
        handleDeleteAll={handleDeleteAll}
      />
    </Box>
  );
});

// Selector to filter records by domainId
const selectRecords = (state) => state.record.records;
const selectDomainId = (_, id) => Number(id);

const filteredRecordsSelector = createSelector(
  [selectRecords, selectDomainId],
  (records, domainId) =>
    records.filter((record) => record.domainId === domainId)
);

const Record = () => {
  const { id } = useParams();
  const records = useSelector((state) => filteredRecordsSelector(state, id));
  const [arrIds, setArrIds] = useState([]);
  const [rowModesModel, setRowModesModel] = useState({});
  const [rows, setRows] = useState(records);
  const {
    editClick,
    deleteClick,
    saveClick,
    rowEditStop,
    cancelClick,
    processRowUpdate,
    rowModesModelChange,
  } = useHelpers(rowModesModel, setRowModesModel, rows, setRows);
  const theme = useTheme();
  const colors = tokens(theme.palette.mode);

  console.log("рендеринг компонента Record");

  React.useEffect(() => {
    setRows(records);
  }, [records]);

  const columns = React.useMemo(
    () => [
      { field: "id", headerName: "ID" },
      {
        field: "type",
        headerName: "Тип",
        type: "string",
        flex: 1,
        editable: true,
      },
      {
        field: "value",
        headerName: "Значение",
        flex: 1,
        type: "string",
        editable: true,
        cellClassName: "name-column--cell",
      },
      {
        field: "priority",
        headerName: "Приоритет",
        type: "number",
        flex: 1,
        editable: true,
      },
      {
        field: "ttl",
        headerName: "Время жизни",
        type: "number",
        flex: 1,
        editable: true,
      },
      {
        field: "actions",
        type: "actions",
        headerName: "Действия",
        width: 100,
        cellClassName: "actions",
        getActions: ({ id }) => {
          const isInEditMode = rowModesModel[id]?.mode === GridRowModes.Edit;

          if (isInEditMode) {
            return [
              <SaveCancelButtons
                id={id}
                saveClick={saveClick}
                cancelClick={cancelClick}
              />,
            ];
          }

          return [
            <GridActionsCellItem
              icon={<EditOutlinedIcon />}
              label="Edit"
              className="textPrimary"
              onClick={() => editClick(id)}
              color="inherit"
            />,
            <GridActionsCellItem
              icon={<DeleteOutlinedIcon />}
              label="Delete"
              onClick={() => deleteClick(id)}
              color="inherit"
            />,
          ];
        },
      },
    ],
    [rowModesModel, editClick, deleteClick, saveClick, cancelClick]
  );

  return (
    <Box
      m="15px"
      sx={{
        "& .MuiDataGrid-root": {
          border: colors.goldAccent[300],
        },
        "& .actions": {
          color: "text.secondary",
        },
        "& .textPrimary": {
          color: "text.primary",
        },
        "& .MuiCheckbox-root": {
          color: `${colors.goldAccent[300]} !important`,
        },
      }}
    >
      <DataGrid
        checkboxSelection
        rows={rows}
        columns={columns}
        editMode="row"
        rowModesModel={rowModesModel}
        onRowModesModelChange={rowModesModelChange}
        onRowEditStop={rowEditStop}
        processRowUpdate={processRowUpdate}
        slots={{
          toolbar: EditToolbar,
        }}
        onRowSelectionModelChange={(ids) => {
          setArrIds(ids);
        }}
        slotProps={{
          toolbar: { setRows, setRowModesModel, rows, arrIds },
        }}
      />
    </Box>
  );
};

export default Record;
